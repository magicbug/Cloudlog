<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Award73On73Plugin {

    private $CI;
    private const TARGET = 73;
    private const SATELLITE = 'AO-73';

    public function __construct($ci)
    {
        $this->CI = $ci;
    }

    public function renderAwardPage($context = array())
    {
        $this->CI->load->model('logbooks_model');

        $active_logbook = $this->CI->session->userdata('active_station_logbook');
        $station_ids = $this->CI->logbooks_model->list_logbook_relationships($active_logbook);

        if (empty($station_ids) || !is_array($station_ids)) {
            return array(
                'page_title' => 'Awards - 73 on 73',
                'content' => '<div class="alert alert-warning">No station locations are associated with your active logbook.</div>',
            );
        }

        $unique_call_rows = $this->fetch_unique_call_rows($station_ids, 'any');
        $lotw_unique_call_rows = $this->fetch_unique_call_rows($station_ids, 'lotw');
        $paper_unique_call_rows = $this->fetch_unique_call_rows($station_ids, 'paper');

        $count = count($unique_call_rows);
        $remaining = max(0, self::TARGET - $count);
        $progress = min(100, (int)floor(($count / self::TARGET) * 100));
        $lotw_count = count($lotw_unique_call_rows);
        $paper_count = count($paper_unique_call_rows);

        $progress_class = 'bg-danger';
        if ($progress >= 100) {
            $progress_class = 'bg-success';
        } elseif ($progress >= 75) {
            $progress_class = 'bg-info';
        } elseif ($progress >= 40) {
            $progress_class = 'bg-primary';
        }

        $status_label = 'Getting Started';
        if ($progress >= 100) {
            $status_label = 'Award Complete';
        } elseif ($progress >= 75) {
            $status_label = 'Final Stretch';
        } elseif ($progress >= 40) {
            $status_label = 'On Track';
        }

        $milestones = array(25, 50, 73);

        $content = '';
        $content .= '<div class="alert alert-light border mb-3">';
        $content .= '<i class="fas fa-satellite me-1"></i>';
        $content .= 'Counts unique callsigns confirmed on <strong>' . htmlspecialchars(self::SATELLITE, ENT_QUOTES, 'UTF-8') . '</strong> via <strong>LoTW</strong> or <strong>paper QSL</strong>.';
        $content .= '</div>';

        $content .= '<div class="row g-3">';
        $content .= '<div class="col-md-4">';
        $content .= '<div class="card h-100"><div class="card-body">';
        $content .= '<div class="d-flex justify-content-between align-items-center mb-2">';
        $content .= '<h5 class="card-title mb-0">73 on 73</h5>';
        $content .= '<span class="badge bg-secondary">' . htmlspecialchars($status_label, ENT_QUOTES, 'UTF-8') . '</span>';
        $content .= '</div>';
        $content .= '<p class="text-muted mb-2">Work 73 unique stations on ' . htmlspecialchars(self::SATELLITE, ENT_QUOTES, 'UTF-8') . ' with LoTW or paper confirmation.</p>';
        $content .= '<h2 class="mb-1">' . (int)$count . ' <span class="text-muted fs-5">/ ' . self::TARGET . '</span></h2>';
        $content .= '<div class="progress mb-2" style="height: 1rem;">';
        $content .= '<div class="progress-bar ' . $progress_class . '" role="progressbar" style="width:' . (int)$progress . '%;" aria-valuenow="' . (int)$progress . '" aria-valuemin="0" aria-valuemax="100">' . (int)$progress . '%</div>';
        $content .= '</div>';

        if ($remaining > 0) {
            $content .= '<p class="mb-0"><strong>' . (int)$remaining . '</strong> more unique stations needed.</p>';
        } else {
            $content .= '<p class="mb-0 text-success"><strong>Award complete.</strong> 73 unique stations reached.</p>';
        }

        $content .= '<hr class="my-3">';
        $content .= '<h6 class="mb-2">Milestones</h6>';
        $content .= '<div class="d-flex flex-wrap gap-2">';
        foreach ($milestones as $milestone) {
            $done = $count >= $milestone;
            $badge = $done ? 'bg-success' : 'bg-light text-dark border';
            $icon = $done ? '<i class="fas fa-check-circle me-1"></i>' : '<i class="far fa-circle me-1"></i>';
            $content .= '<span class="badge ' . $badge . '">' . $icon . $milestone . '</span>';
        }
        $content .= '</div>';

        $content .= '</div></div>';

        $content .= '<div class="card mt-3"><div class="card-body">';
        $content .= '<h6 class="mb-3">Confirmation Mix</h6>';
        $content .= '<div class="d-flex justify-content-between"><span>LoTW confirmed</span><strong>' . (int)$lotw_count . '</strong></div>';
        $content .= '<div class="d-flex justify-content-between"><span>Paper confirmed</span><strong>' . (int)$paper_count . '</strong></div>';
        $content .= '</div></div>';

        $content .= '</div>';

        $content .= '<div class="col-md-8">';
        $content .= '<div class="card h-100"><div class="card-body">';
        $content .= '<div class="d-flex justify-content-between align-items-center mb-2">';
        $content .= '<h5 class="card-title mb-0">Unique Confirmed Stations on ' . htmlspecialchars(self::SATELLITE, ENT_QUOTES, 'UTF-8') . '</h5>';
        $content .= '<div class="d-flex align-items-center gap-2">';
        if ($count > 0) {
            $content .= '<button type="button" onclick="exportTableToCSV(\'award_73_on_73_table.csv\')" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Export CSV</button>';
        }
        $content .= '<span class="badge bg-dark">' . (int)$count . ' total</span>';
        $content .= '</div>';
        $content .= '</div>';

        if ($count === 0) {
            $content .= '<p class="text-muted mb-0">No qualifying QSOs found yet.</p>';
        } else {
            $content .= '<div class="table-responsive" style="max-height: 380px; overflow-y: auto;">';
            $content .= '<table id="award-73-on-73-table" class="table table-sm table-striped table-hover mb-0">';
            $content .= '<thead class="table-light" style="position: sticky; top: 0; z-index: 1;"><tr><th style="width:80px;">#</th><th style="width:180px;">QSO Date/Time</th><th>Callsign</th><th style="width:120px;">Mode</th><th style="width:140px;">GridSquare</th></tr></thead><tbody>';

            $idx = 1;
            foreach ($unique_call_rows as $row) {
                $content .= '<tr><td>' . $idx . '</td><td>' . htmlspecialchars($this->format_qso_datetime($row['qso_time']), ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($row['call'], ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($this->format_display_value($row['mode']), ENT_QUOTES, 'UTF-8') . '</td><td>' . htmlspecialchars($this->format_display_value($row['gridsquare']), ENT_QUOTES, 'UTF-8') . '</td></tr>';
                $idx++;
            }

            $content .= '</tbody></table></div>';
        }

        $content .= '</div></div></div>';
        $content .= '</div>';

        $content .= '<script>
function exportTableToCSV(filename) {
    const table = document.getElementById("award-73-on-73-table");
    if (!table) {
        return;
    }

    let csv = [];

    const headers = Array.from(table.querySelectorAll("thead th")).map(function(h) {
        return "\"" + h.textContent.trim().replace(/\"/g, "\"\"") + "\"";
    });
    csv.push(headers.join(","));

    const rows = table.querySelectorAll("tbody tr");
    rows.forEach(function(row) {
        const cells = Array.from(row.querySelectorAll("td")).map(function(cell) {
            return "\"" + cell.textContent.trim().replace(/\"/g, "\"\"") + "\"";
        });
        csv.push(cells.join(","));
    });

    const blob = new Blob([csv.join("\\n")], { type: "text/csv;charset=utf-8" });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>';

        return array(
            'page_title' => 'Awards - 73 on 73',
            'content' => $content,
        );
    }

    private function fetch_unique_call_rows($station_ids, $confirmation = 'any')
    {
        $table = $this->CI->config->item('table_name');

        $this->CI->db->select('UPPER(TRIM(COL_CALL)) as COL_CALL, COL_TIME_ON, COL_MODE, COL_GRIDSQUARE, COL_VUCC_GRIDS', false);
        $this->CI->db->from($table);
        $this->CI->db->where_in('station_id', $station_ids);
        $this->CI->db->where('UPPER(TRIM(COL_SAT_NAME))', self::SATELLITE);
        $this->CI->db->where('UPPER(TRIM(COL_CALL)) !=', '');

        if ($confirmation === 'lotw') {
            $this->CI->db->where('COL_LOTW_QSL_RCVD', 'Y');
        } elseif ($confirmation === 'paper') {
            $this->CI->db->where('COL_QSL_RCVD', 'Y');
        } else {
            $this->CI->db->group_start();
            $this->CI->db->where('COL_LOTW_QSL_RCVD', 'Y');
            $this->CI->db->or_where('COL_QSL_RCVD', 'Y');
            $this->CI->db->group_end();
        }

        $this->CI->db->order_by('COL_CALL', 'ASC');
        $this->CI->db->order_by('COL_TIME_ON', 'ASC');
        $query = $this->CI->db->get();
        $rows = $query ? $query->result() : array();

        $result_rows_by_call = array();
        foreach ($rows as $row) {
            $call = strtoupper(trim((string)($row->COL_CALL ?? '')));
            if ($call !== '' && !array_key_exists($call, $result_rows_by_call)) {
                $vucc_grids = strtoupper(trim((string)($row->COL_VUCC_GRIDS ?? '')));
                $gridsquare = strtoupper(trim((string)($row->COL_GRIDSQUARE ?? '')));
                $result_rows_by_call[$call] = array(
                    'call' => $call,
                    'qso_time' => (string)($row->COL_TIME_ON ?? ''),
                    'mode' => trim((string)($row->COL_MODE ?? '')),
                    'gridsquare' => $vucc_grids !== '' ? $vucc_grids : $gridsquare,
                );
            }
        }

        return array_values($result_rows_by_call);
    }

    private function format_qso_datetime($qso_time)
    {
        $qso_time = trim((string)$qso_time);
        if ($qso_time === '') {
            return '-';
        }

        $timestamp = strtotime($qso_time);
        if ($timestamp === false) {
            return '-';
        }

        $custom_date_format = $this->CI->session->userdata('user_date_format');
        if (!$custom_date_format) {
            $custom_date_format = $this->CI->config->item('qso_date_format');
        }

        return date($custom_date_format, $timestamp) . ' ' . date('H:i', $timestamp);
    }

    private function format_display_value($value)
    {
        $value = trim((string)$value);
        return $value === '' ? '-' : $value;
    }
}
