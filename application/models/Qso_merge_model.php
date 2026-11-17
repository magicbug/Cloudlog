<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Qso_merge_model extends CI_Model
{
    private const LINKED_TABLES = [
        ['table' => 'qsl_images', 'column' => 'qsoid'],
        ['table' => 'eQSL_images', 'column' => 'qso_id'],
        ['table' => 'sstv_images', 'column' => 'qsoid'],
        ['table' => 'webadif', 'column' => 'qso_id'],
        ['table' => 'oqrs', 'column' => 'qsoid'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('logbook_model');
        $this->load->library('Qso_merge_field_chooser', null, 'merge_chooser');
    }

  /**
   * @param int[] $ids
   * @return array{ok: bool, message?: string, qsos?: array<int, array<string, mixed>>}
   */
    public function load_merge_group(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (count($ids) < 2) {
            return ['ok' => false, 'message' => 'At least two QSOs are required to merge.'];
        }

        $qsos = [];
        foreach ($ids as $id) {
            if (!$this->logbook_model->check_qso_is_writable($id)) {
                return ['ok' => false, 'message' => 'You do not have permission to merge one or more of these QSOs.'];
            }
            $query = $this->logbook_model->qso_info($id);
            if (!$query || $query->num_rows() !== 1) {
                return ['ok' => false, 'message' => 'One or more QSOs could not be found.'];
            }
            $row = $query->row_array();
            $qsos[(int) $row['COL_PRIMARY_KEY']] = $row;
        }

        if (!$this->is_valid_duplicate_group($qsos)) {
            return ['ok' => false, 'message' => 'These QSOs do not form a valid duplicate group.'];
        }

        return ['ok' => true, 'qsos' => $qsos];
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   */
    public function is_valid_duplicate_group(array $qsos): bool
    {
        if (count($qsos) < 2) {
            return false;
        }

        $rows = array_values($qsos);
        $first = $rows[0];

        $keys = ['COL_CALL', 'COL_MODE', 'COL_SUBMODE', 'COL_STATION_CALLSIGN', 'COL_BAND', 'COL_SAT_NAME'];
        foreach ($rows as $row) {
            foreach ($keys as $key) {
                if ($this->normalise_group_value($row[$key] ?? null) !== $this->normalise_group_value($first[$key] ?? null)) {
                    return false;
                }
            }
        }

        $times = [];
        foreach ($rows as $row) {
            $ts = strtotime($row['COL_TIME_ON'] ?? '');
            if ($ts !== false) {
                $times[] = $ts;
            }
        }

        if (count($times) < 2) {
            return false;
        }

        return (max($times) - min($times)) < 3000;
    }

  /**
   * @param int[] $delete_ids
   * @param array<string, int> $field_sources field => source COL_PRIMARY_KEY
   * @return array{ok: bool, message?: string, keeper_id?: int}
   */
    public function execute_merge(int $keeper_id, array $delete_ids, array $field_sources): array
    {
        $delete_ids = array_values(array_diff(array_map('intval', $delete_ids), [$keeper_id]));
        $all_ids = array_merge([$keeper_id], $delete_ids);

        $loaded = $this->load_merge_group($all_ids);
        if (!$loaded['ok']) {
            return ['ok' => false, 'message' => $loaded['message']];
        }

        $qsos = $loaded['qsos'];
        $fields = $this->merge_chooser->mergeable_fields($qsos);
        $update = [];

        foreach ($fields as $field) {
            $source_id = isset($field_sources[$field]) ? (int) $field_sources[$field] : $keeper_id;
            if (!isset($qsos[$source_id])) {
                return ['ok' => false, 'message' => 'Invalid field selection for ' . $field];
            }
            if ($field === 'station_id') {
                continue;
            }
            $update[$field] = $qsos[$source_id][$field] ?? null;
        }

        $update['station_id'] = $qsos[$keeper_id]['station_id'];

        $table = $this->config->item('table_name');

        $this->db->trans_start();

        foreach ($delete_ids as $old_id) {
            $this->repoint_linked_records($old_id, $keeper_id);
        }

        $this->db->where('COL_PRIMARY_KEY', $keeper_id);
        $this->db->update($table, $update);

        $station_ids_for_cache = [];
        foreach ($delete_ids as $old_id) {
            $station_id = $this->logbook_model->remove_qso_record($old_id);
            if ($station_id !== null) {
                $station_ids_for_cache[] = (int) $station_id;
            }
        }

        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return ['ok' => false, 'message' => 'Merge failed. No changes were saved.'];
        }

        $station_ids_for_cache[] = (int) $qsos[$keeper_id]['station_id'];
        foreach (array_unique($station_ids_for_cache) as $station_id) {
            $this->logbook_model->invalidate_dashboard_cache($station_id);
        }

        return ['ok' => true, 'keeper_id' => $keeper_id];
    }

    public function build_merged_preview(int $keeper_id, array $qsos, array $field_sources): array
    {
        $fields = $this->merge_chooser->mergeable_fields($qsos);
        $preview = [];
        foreach ($fields as $field) {
            $source_id = isset($field_sources[$field]) ? (int) $field_sources[$field] : $keeper_id;
            $preview[$field] = $qsos[$source_id][$field] ?? null;
        }

        return $preview;
    }

    private function repoint_linked_records(int $from_id, int $to_id): void
    {
        foreach (self::LINKED_TABLES as $spec) {
            if (!$this->db->table_exists($spec['table'])) {
                continue;
            }
            if ($spec['table'] === 'webadif') {
                $this->repoint_webadif($from_id, $to_id);
                continue;
            }
            $this->db->where($spec['column'], $from_id);
            $this->db->update($spec['table'], [$spec['column'] => $to_id]);
        }
    }

    private function repoint_webadif(int $from_id, int $to_id): void
    {
        $rows = $this->db->where('qso_id', $from_id)->get('webadif')->result_array();
        foreach ($rows as $row) {
            $exists = $this->db
                ->where('qso_id', $to_id)
                ->where('upload_date', $row['upload_date'])
                ->count_all_results('webadif');
            if ($exists > 0) {
                $this->db->where('id', $row['id'])->delete('webadif');
            } else {
                $this->db->where('id', $row['id'])->update('webadif', ['qso_id' => $to_id]);
            }
        }
    }

    private function normalise_group_value($value): string
    {
        if ($value === null) {
            return '';
        }

        return strtoupper(trim((string) $value));
    }
}
