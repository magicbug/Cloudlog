<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Picks default field values when merging duplicate QSOs.
 */
class Qso_merge_field_chooser
{
    private const QSL_STATUS_FIELDS = [
        'COL_QSL_SENT',
        'COL_QSL_RCVD',
        'COL_LOTW_QSL_SENT',
        'COL_LOTW_QSL_RCVD',
        'COL_EQSL_QSL_SENT',
        'COL_EQSL_QSL_RCVD',
        'COL_CLUBLOG_QSO_UPLOAD_STATUS',
        'COL_QRZCOM_QSO_UPLOAD_STATUS',
        'COL_QRZCOM_QSO_DOWNLOAD_STATUS',
    ];

    private const QSL_DATE_FIELDS = [
        'COL_QSLSDATE',
        'COL_QSLRDATE',
        'COL_LOTW_QSLSDATE',
        'COL_LOTW_QSLRDATE',
        'COL_EQSL_QSLSDATE',
        'COL_EQSL_QSLRDATE',
        'COL_CLUBLOG_QSO_UPLOAD_DATE',
        'COL_QRZCOM_QSO_UPLOAD_DATE',
        'COL_QRZCOM_QSO_DOWNLOAD_DATE',
    ];

  /**
   * @param array<int, array<string, mixed>> $qsos keyed by COL_PRIMARY_KEY
   * @return array<string, int> field => source COL_PRIMARY_KEY
   */
    public function default_choices(array $qsos): array
    {
        if ($qsos === []) {
            return [];
        }

        $fields = $this->mergeable_fields($qsos);
        $choices = [];

        foreach ($fields as $field) {
            $choices[$field] = $this->pick_source($qsos, $field);
        }

        return $choices;
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   */
    public function suggest_keeper_id(array $qsos): int
    {
        $best_id = null;
        $best_score = -1;

        foreach ($qsos as $id => $row) {
            $score = $this->keeper_score($row);
            if ($score > $best_score) {
                $best_score = $score;
                $best_id = (int) $id;
            }
        }

        return $best_id ?? (int) array_key_first($qsos);
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   * @return list<string>
   */
    public function mergeable_fields(array $qsos): array
    {
        $fields = [];
        foreach ($qsos as $row) {
            if (!is_array($row)) {
                continue;
            }
            foreach (array_keys($row) as $key) {
                if ($key === 'COL_PRIMARY_KEY' || in_array($key, $fields, true)) {
                    continue;
                }
                if (strpos($key, 'COL_') === 0 || $key === 'station_id') {
                    $fields[] = $key;
                }
            }
        }

        sort($fields);

        return $fields;
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   */
    private function pick_source(array $qsos, string $field): int
    {
        if (in_array($field, self::QSL_STATUS_FIELDS, true)) {
            return $this->pick_best_status_source($qsos, $field);
        }

        if (in_array($field, self::QSL_DATE_FIELDS, true)) {
            return $this->pick_best_date_source($qsos, $field);
        }

        return $this->pick_best_nonempty_source($qsos, $field);
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   */
    private function pick_best_status_source(array $qsos, string $field): int
    {
        $best_id = (int) array_key_first($qsos);
        $best_rank = -1;

        foreach ($qsos as $id => $row) {
            $rank = $this->status_rank($row[$field] ?? null);
            if ($rank > $best_rank) {
                $best_rank = $rank;
                $best_id = (int) $id;
            }
        }

        return $best_id;
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   */
    private function pick_best_date_source(array $qsos, string $field): int
    {
        $best_id = (int) array_key_first($qsos);
        $best_ts = null;

        foreach ($qsos as $id => $row) {
            $value = $row[$field] ?? null;
            if ($value === null || $value === '' || $value === '0000-00-00 00:00:00') {
                continue;
            }
            $ts = strtotime((string) $value);
            if ($ts === false) {
                continue;
            }
            if ($best_ts === null || $ts > $best_ts) {
                $best_ts = $ts;
                $best_id = (int) $id;
            }
        }

        if ($best_ts !== null) {
            return $best_id;
        }

        return $this->pick_best_nonempty_source($qsos, $field);
    }

  /**
   * @param array<int, array<string, mixed>> $qsos
   */
    private function pick_best_nonempty_source(array $qsos, string $field): int
    {
        $best_id = (int) array_key_first($qsos);

        foreach ($qsos as $id => $row) {
            if ($this->is_nonempty($row[$field] ?? null)) {
                return (int) $id;
            }
        }

        return $best_id;
    }

    private function status_rank($value): int
    {
        $v = strtoupper(trim((string) $value));
        if ($v === 'Y') {
            return 100;
        }
        if ($v === 'I') {
            return 80;
        }
        if ($v === 'R') {
            return 60;
        }
        if ($v === 'M') {
            return 40;
        }
        if ($v === 'N') {
            return 20;
        }
        if ($this->is_nonempty($value)) {
            return 10;
        }

        return 0;
    }

    private function keeper_score(array $row): int
    {
        $score = 0;
        foreach (self::QSL_STATUS_FIELDS as $field) {
            $score += $this->status_rank($row[$field] ?? null);
        }
        foreach (array_keys($row) as $field) {
            if (strpos($field, 'COL_') === 0 && $this->is_nonempty($row[$field] ?? null)) {
                $score += 1;
            }
        }

        return $score;
    }

    private function is_nonempty($value): bool
    {
        if ($value === null) {
            return false;
        }
        if (is_string($value) && trim($value) === '') {
            return false;
        }

        return true;
    }
}
