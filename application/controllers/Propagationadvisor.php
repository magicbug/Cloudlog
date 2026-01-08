<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Propagationadvisor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
        if ($this->user_model->validate_session() == 0) {
            redirect('user/login');
        }

        if (!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', "You're not allowed to do that!");
            redirect('dashboard');
        }

        $this->load->model('logbooks_model');
        $this->load->model('logbook_model');
        $this->load->model('bands');
        $this->lang->load(array('statistics', 'general_words', 'menu'));
    }

    public function index()
    {
        $data['page_title'] = lang('propagation_title');
        $data['dxcc_list'] = $this->logbook_model->fetchDxcc();
        $bands = $this->bands->get_worked_bands();
        $data['bands'] = array_values(array_filter($bands, function($band) {
            return strtoupper($band) !== 'SAT';
        }));
        $data['modes'] = $this->logbook_model->get_modes()->result();

        $this->load->view('interface_assets/header', $data);
        $this->load->view('propagationadvisor/index', $data);
        $this->load->view('interface_assets/footer');
    }

    public function data()
    {
        $filters = $this->build_filters();
        if ($filters === null) {
            return $this->respond_error(lang('propagation_required_filters'));
        }

        $stationIds = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        if (!$stationIds) {
            return $this->respond_error(lang('error_no_active_station_profile'));
        }

        $hourlyRows = $this->logbook_model->get_propagation_hourly($filters, $stationIds);
        $hourly = array_fill(0, 24, 0);
        foreach ($hourlyRows as $row) {
            $hour = (int)$row->hour_bucket;
            if ($hour >= 0 && $hour <= 23) {
                $hourly[$hour] = (int)$row->total;
            }
        }

        $hourlyByBandRows = $this->logbook_model->get_propagation_hourly_by_band($filters, $stationIds);
        $hourlyByBand = $this->format_hourly_by_band($hourlyByBandRows);

        $bandBreakdown = $this->logbook_model->get_propagation_band_breakdown($filters, $stationIds);
        $modeBreakdown = $this->logbook_model->get_propagation_mode_breakdown($filters, $stationIds);
        $lastWorked = $this->logbook_model->get_propagation_last_worked($filters, $stationIds);
        $trendRows = $this->logbook_model->get_propagation_activity_trend($filters, $stationIds, 90);
        $trend = $this->format_trend($trendRows, 90);

        $response = array(
            'success' => true,
            'filters' => $filters,
            'hourly' => $hourly,
            'hourly_by_band' => $hourlyByBand,
            'total_qsos' => array_sum($hourly),
            'best_window' => $this->compute_best_window($hourly),
            'best_band' => $this->first_or_null($bandBreakdown, 'band'),
            'best_mode' => $this->first_or_null($modeBreakdown, 'mode'),
            'last_worked' => $lastWorked,
            'band_breakdown' => $this->format_breakdown($bandBreakdown, 'band'),
            'trend' => $trend,
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function export()
    {
        $filters = $this->build_filters();
        if ($filters === null) {
            show_error(lang('propagation_required_filters'), 400);
            return;
        }

        $stationIds = $this->logbooks_model->list_logbook_relationships($this->session->userdata('active_station_logbook'));
        if (!$stationIds) {
            show_error(lang('error_no_active_station_profile'), 400);
            return;
        }

        $hourlyRows = $this->logbook_model->get_propagation_hourly($filters, $stationIds);
        $hourly = array_fill(0, 24, 0);
        foreach ($hourlyRows as $row) {
            $hour = (int)$row->hour_bucket;
            if ($hour >= 0 && $hour <= 23) {
                $hourly[$hour] = (int)$row->total;
            }
        }

        $filename = 'propagation_advisor_' . date('Ymd_His') . '.csv';
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Type: application/csv;charset=iso-8859-1');

        $fp = fopen('php://output', 'w');
        fputcsv($fp, array('Hour (UTC)', 'QSOs'));
        foreach ($hourly as $hour => $count) {
            fputcsv($fp, array(str_pad($hour, 2, '0', STR_PAD_LEFT), $count));
        }
        fclose($fp);
    }

    private function build_filters()
    {
        $dxcc = $this->input->get_post('dxcc', true);
        if (empty($dxcc)) {
            return null;
        }

        $band = $this->input->get_post('band', true);
        $mode = $this->input->get_post('mode', true);

        return array(
            'dxcc' => (int) $dxcc,
            'band' => $band ? trim($band) : '',
            'mode' => $mode ? trim($mode) : '',
        );
    }

    private function compute_best_window(array $hourly, $window = 3)
    {
        if (empty($hourly)) {
            return null;
        }

        $bestStart = null;
        $bestTotal = 0;

        for ($start = 0; $start <= 23 - $window + 1; $start++) {
            $sliceTotal = 0;
            for ($i = 0; $i < $window; $i++) {
                $sliceTotal += $hourly[$start + $i];
            }
            if ($sliceTotal > $bestTotal) {
                $bestTotal = $sliceTotal;
                $bestStart = $start;
            }
        }

        if ($bestTotal === 0 || $bestStart === null) {
            return null;
        }

        return array(
            'start' => $bestStart,
            'end' => $bestStart + $window - 1,
            'total' => $bestTotal,
            'label' => str_pad($bestStart, 2, '0', STR_PAD_LEFT) . '-' . str_pad($bestStart + $window - 1, 2, '0', STR_PAD_LEFT) . ' UTC',
        );
    }

    private function first_or_null($collection, $key)
    {
        if (empty($collection)) {
            return null;
        }

        $first = $collection[0];
        if (!isset($first->$key)) {
            return null;
        }

        return array(
            'value' => $first->$key,
            'total' => isset($first->total) ? (int)$first->total : null,
        );
    }

    private function format_breakdown($collection, $key)
    {
        $output = array();
        foreach ($collection as $item) {
            if (isset($item->$key)) {
                $output[] = array(
                    'label' => $item->$key,
                    'total' => (int)$item->total,
                );
            }
        }
        return $output;
    }

    private function format_hourly_by_band($rows)
    {
        $result = array();
        foreach ($rows as $row) {
            $band = $row->band;
            $hour = (int)$row->hour_bucket;
            $total = (int)$row->total;

            if (!isset($result[$band])) {
                $result[$band] = array_fill(0, 24, 0);
            }

            if ($hour >= 0 && $hour <= 23) {
                $result[$band][$hour] = $total;
            }
        }

        // Order bands by frequency (reverse order so lowest frequency at top)
        $this->load->model('bands');
        $orderedBands = $this->bands->get_user_bands();
        $orderedResult = array();
        foreach ($orderedBands as $band) {
            if (isset($result[$band])) {
                $orderedResult[$band] = $result[$band];
            }
        }

        return $orderedResult;
    }

    private function format_trend($rows, $days)
    {
        // Build date-indexed array for last $days days (inclusive of today)
        $daily = array();
        for ($i = $days - 1; $i >= 0; $i--) {
            $dateKey = date('Y-m-d', strtotime("-$i days"));
            $daily[$dateKey] = 0;
        }

        foreach ($rows as $row) {
            $day = $row->day;
            if (isset($daily[$day])) {
                $daily[$day] = (int)$row->total;
            }
        }

        $dates = array_keys($daily);
        $counts = array_values($daily);

        $last7 = array_sum(array_slice($counts, -7));
        $prev7 = array_sum(array_slice($counts, -14, 7));
        $last30 = array_sum(array_slice($counts, -30));
        $prev30 = array_sum(array_slice($counts, -60, 30));
        $last90 = array_sum($counts);

        return array(
            'dates' => $dates,
            'counts' => $counts,
            'last_7' => $last7,
            'prev_7' => $prev7,
            'last_30' => $last30,
            'prev_30' => $prev30,
            'last_90' => $last90,
        );
    }

    private function respond_error($message)
    {
        return $this->output
            ->set_status_header(400)
            ->set_content_type('application/json')
            ->set_output(json_encode(array('success' => false, 'message' => $message)));
    }
}
