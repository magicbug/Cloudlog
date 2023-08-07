<?php
use Cloudlog\Dxcc\Dxcc;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calltester extends CI_Controller {
	function __construct() {
		parent::__construct();

		$this->load->model('user_model');
		if(!$this->user_model->authorize(99)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}


	public function db() {
        set_time_limit(3600);

        // Starting clock time in seconds
        $start_time = microtime(true);

		$this->load->model('logbook_model');

        $sql = 'select distinct col_country, col_call, col_dxcc, date(col_time_on) date from ' . $this->config->item('table_name');
        $query = $this->db->query($sql);

        $callarray = $query->result();

        $result = array();

        $i = 0;

        foreach ($callarray as $call) {
            $i++;
            $dxcc = $this->logbook_model->dxcc_lookup($call->col_call, $call->date);

            $dxcc['adif'] = (isset($dxcc['adif'])) ? $dxcc['adif'] : 0;
            $dxcc['entity'] = (isset($dxcc['entity'])) ? $dxcc['entity'] : 0;

            if ($call->col_dxcc != $dxcc['adif']) {
                $result[] = array(
                                'Callsign'          => $call->col_call,
                                'Expected country'  => $call->col_country,
                                'Expected adif'     => $call->col_dxcc,
                                'Result country'    => ucwords(strtolower($dxcc['entity']), "- (/"),
                                'Result adif'       => $dxcc['adif'],
                            );
            }
        }

        // End clock time in seconds
        $end_time = microtime(true);

        // Calculate script execution time
        $execution_time = ($end_time - $start_time);

        echo " Execution time of script = ".$execution_time." sec <br/>";
        echo $i . " calls tested. <br/>";
        $count = 0;

        if ($result) {
            $this->array_to_table($result);
        }

	}


    function array_to_table($table) {
        echo '<style>
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        table td, table th {
            border: 1px solid #ddd;
            padding: 4px;
        }

        table tr:nth-child(even){background-color: #f2f2f2;}

        table tr:hover {background-color: #ddd;}

        table th {
            padding-top: 4px;
            padding-bottom: 4px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
        </style> ';

       echo '<table>';

       // Table header
        foreach ($table[0] as $key=>$value) {
            echo "<th>".$key."</th>";
        }

        // Table body
        foreach ($table as $value) {
            echo "<tr>";
            foreach ($value as $val) {
                    echo "<td>".$val."</td>";
            }
            echo "</tr>";
        }
       echo "</table>";
    }

    function csv() {
        set_time_limit(3600);

        // Starting clock time in seconds
        $start_time = microtime(true);

		$this->load->model('logbook_model');

        $file = 'uploads/calls.csv';
        $handle = fopen($file,"r");

        $data = fgetcsv($handle,1000,","); // Skips firsts line, usually that is the header
        $data = fgetcsv($handle,1000,",");

        $result = array();

        $i = 0;

        do {
            if ($data[0]) {
                // COL_CALL,COL_DXCC,COL_TIME_ON
                $i++;

                $dxcc = $this->logbook_model->dxcc_lookup($data[0], $data[2]);

                $dxcc['adif'] = (isset($dxcc['adif'])) ? $dxcc['adif'] : 0;
                $dxcc['entity'] = (isset($dxcc['entity'])) ? $dxcc['entity'] : 0;

                $data[1] = $data[1] == "NULL" ? 0 : $data[1];

                if ($data[1] != $dxcc['adif']) {
                    $result[] = array(
                                    'Callsign'          => $data[0],
                                    'Expected country'  => '',
                                    'Expected adif'     => $data[1],
                                    'Result country'    => ucwords(strtolower($dxcc['entity']), "- (/"),
                                    'Result adif'       => $dxcc['adif'],
                                );
                }
            }
        } while ($data = fgetcsv($handle,1000,","));

        // End clock time in seconds
        $end_time = microtime(true);

        // Calculate script execution time
        $execution_time = ($end_time - $start_time);

        echo " Execution time of script = ".$execution_time." sec <br/>";
        echo $i . " calls tested. <br/>";
        $count = 0;

        if ($result) {
            $this->array_to_table($result);
        }
    }

    /*
     * Uses check_dxcc_table - written to check if that function works
     */
    function csv2() {
        set_time_limit(3600);

        // Starting clock time in seconds
        $start_time = microtime(true);

		$this->load->model('logbook_model');

        $file = 'uploads/calls.csv';
        $handle = fopen($file,"r");

        $data = fgetcsv($handle,1000,","); // Skips firsts line, usually that is the header
        $data = fgetcsv($handle,1000,",");

        $result = array();

        $i = 0;

        do {
            if ($data[0]) {
                // COL_CALL,COL_DXCC,COL_TIME_ON
                $i++;

                $dxcc = $this->logbook_model->check_dxcc_table($data[0], $data[2]);

                $data[1] = $data[1] == "NULL" ? 0 : $data[1];

                if ($data[1] != $dxcc[0]) {
                    $result[] = array(
                                    'Callsign'          => $data[0],
                                    'Expected country'  => '',
                                    'Expected adif'     => $data[1],
                                    'Result country'    => ucwords(strtolower($dxcc[1]), "- (/"),
                                    'Result adif'       => $dxcc[0],
                                );
                }
            }
        } while ($data = fgetcsv($handle,1000,","));

        // End clock time in seconds
        $end_time = microtime(true);

        // Calculate script execution time
        $execution_time = ($end_time - $start_time);

        echo " Execution time of script = ".$execution_time." sec <br/>";
        echo $i . " calls tested. <br/>";
        $count = 0;

        if ($result) {
            $this->array_to_table($result);
        }
    }

    function call() {
        $testarray = array();

        $testarray[] = array(
            'Callsign'  => 'VE3EY/VP9',
            'Country'   => 'Bermuda',
            'Adif'      => 64,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'VP2MDG',
            'Country'   => 'Montserrat',
            'Adif'      => 96,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'VP2EY',
            'Country'   => 'Anguilla',
            'Adif'      => 12,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'VP2VI',
            'Country'   => 'British Virgin Islands.',
            'Adif'      => 65,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'VP2V/AA7V',
            'Country'   => 'British Virgin Islands',
            'Adif'      => 65,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'W8LR/R',
            'Country'   => 'United States Of America',
            'Adif'      => 291,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'SO1FH',
            'Country'   => 'Poland',
            'Adif'      => 269,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'KZ1H/PP',
            'Country'   => 'Brazil',
            'Adif'      => 108,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'K1KW/AM',
            'Country'   => 'None',
            'Adif'      => 0,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'K1KW/MM',
            'Country'   => 'None',
            'Adif'      => 0,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'TF/DL2NWK/P',
            'Country'   => 'Iceland',
            'Adif'      => 242,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'OZ1ALS/A',
            'Country'   => 'Denmark',
            'Adif'      => 221,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'LA1K',
            'Country'   => 'Norway',
            'Adif'      => 266,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'K1KW/M',
            'Country'   => 'United States Of America',
            'Adif'      => 291,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'TF/DL2NWK/M',
            'Country'   => 'Iceland',
            'Adif'      => 242,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'TF/DL2NWK/MM',
            'Country'   => 'None',
            'Adif'      => 0,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'TF/DL2NWK/P',
            'Country'   => 'Iceland',
            'Adif'      => 242,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => '2M0SQL/P',
            'Country'   => 'Scotland',
            'Adif'      => 279,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'FT8WW',
            'Country'   => 'Crozet Island',
            'Adif'      => 41,
            'Date'      => 20230314
        );

        $testarray[] = array(
            'Callsign'  => 'RV0AL/0/P',
            'Country'   => 'Asiatic Russia',
            'Adif'      => 15,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'OH/DJ1YFK',
            'Country'   => 'Finland',
            'Adif'      => 224,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'N6TR/7',
            'Country'   => 'United States Of America',
            'Adif'      => 291,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'KH0CW',
            'Country'   => 'United States Of America',
            'Adif'      => 291,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'R2FM/P',
            'Country'   => 'kaliningrad',
            'Adif'      => 126,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'R2FM',
            'Country'   => 'kaliningrad',
            'Adif'      => 126,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'FT5XO',
            'Country'   => 'Kerguelen Island',
            'Adif'      => 131,
            'Date'      => 20050320
        );

        $testarray[] = array(
            'Callsign'  => 'VP8CTR',
            'Country'   => 'Antarctica',
            'Adif'      => 13,
            'Date'      => 19970207
        );

        $testarray[] = array(
            'Callsign'  => 'FO0AAA',
            'Country'   => 'Clipperton',
            'Adif'      => 36,
            'Date'      => '20000302'
        );

        $testarray[] = array(
            'Callsign'  => 'CX/PR8KW',
            'Country'   => 'Uruguay',
            'Adif'      => 144,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'IQ3MV/LH',
            'Country'   => 'Italy',
            'Adif'      => 248,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'LA1K/QRP',
            'Country'   => 'Norway',
            'Adif'      => 266,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'LA1K/LGT',
            'Country'   => 'Norway',
            'Adif'      => 266,
            'Date'      => $date = date('Ymd', time())
        );

        $testarray[] = array(
            'Callsign'  => 'SM1K/LH',
            'Country'   => 'Sweden',
            'Adif'      => 284,
            'Date'      => $date = date('Ymd', time())
        );

		$testarray[] = array(
            'Callsign'  => 'KG4W',
            'Country'   => 'United States Of America',
            'Adif'      => 291,
            'Date'      => $date = date('Ymd', time())
        );

		$testarray[] = array(
            'Callsign'  => 'KG4WW',
            'Country'   => 'Guantanamo Bay',
            'Adif'      => 105,
            'Date'      => $date = date('Ymd', time())
        );

		$testarray[] = array(
            'Callsign'  => 'KG4WWW',
            'Country'   => 'United States Of America',
            'Adif'      => 291,
            'Date'      => $date = date('Ymd', time())
        );

        set_time_limit(3600);

        // Starting clock time in seconds
        $start_time = microtime(true);

		$this->load->model('logbook_model');

        $result = array();

        $i = 0;

        foreach ($testarray as $call) {
            $i++;
            $dxcc = $this->logbook_model->dxcc_lookup($call['Callsign'], $call['Date']);

            $dxcc['adif'] = (isset($dxcc['adif'])) ? $dxcc['adif'] : 0;
            $dxcc['entity'] = (isset($dxcc['entity'])) ? $dxcc['entity'] : 0;

            if ($call['Adif'] != $dxcc['adif']) {
                $result[] = array(
                                'Callsign'          => $call['Callsign'],
                                'Expected country'  => $call['Country'],
                                'Expected adif'     => $call['Adif'],
                                'Result country'    => ucwords(strtolower($dxcc['entity']), "- (/"),
                                'Result adif'       => $dxcc['adif'],
                            );
            }
        }

        // End clock time in seconds
        $end_time = microtime(true);

        // Calculate script execution time
        $execution_time = ($end_time - $start_time);

        echo " Execution time of script = ".$execution_time." sec <br/>";
        echo $i . " calls tested. <br/>";
        $count = 0;

        if ($result) {
            $this->array_to_table($result);
        }
    }
}
