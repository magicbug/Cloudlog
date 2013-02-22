<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lotw extends CI_Controller {

	/* Controls who can access the controller and its functions */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		
		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function import() {	
		$data['page_title'] = "LoTW ADIF Import";

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			
			$data['error'] = $this->upload->display_errors();

			$this->load->view('layout/header', $data);
			$this->load->view('lotw/import');
			$this->load->view('layout/footer');
		}
		else
		{
			if ($this->input->post('lotwimport') == 'upload')
			{
				$data = array('upload_data' => $this->upload->data());
			
				ini_set('memory_limit', '-1');
				set_time_limit(0);
	
				$this->load->model('logbook_model');
	
				$this->load->library('adif_parser');

				$this->adif_parser->load_from_file('./uploads/'.$data['upload_data']['file_name']);
	
				$this->adif_parser->initialize();
	
				$table = "<table>";
			
				while($record = $this->adif_parser->get_record())
				{
					if(count($record) == 0)
					{
						break;
					};

				

					//echo date('Y-m-d', strtotime($record['qso_date']))."<br>";
					//echo date('H:m', strtotime($record['time_on']))."<br>";

					//$this->logbook_model->import($record);

					//echo $record["call"]."<br>";
					//print_r($record->);
				
					$time_on = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));
				
					$qsl_date = date('Y-m-d', strtotime($record['qslrdate'])) ." ".date('H:i', strtotime($record['qslrdate']));

					if (isset($record['time_off'])) {
						$time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_off']));
					} else {
					   $time_off = date('Y-m-d', strtotime($record['qso_date'])) ." ".date('H:i', strtotime($record['time_on']));  
					}
				
					$status = $this->logbook_model->import_check($time_on, $record['call'], $record['band']);
					$lotw_status = $this->logbook_model->lotw_update($time_on, $record['call'], $record['band'], $qsl_date, $record['qsl_rcvd']);
				
					$table .= "<tr>";
						$table .= "<td>".$time_on."</td>";
						$table .= "<td>".$record['call']."</td>";
						$table .= "<td>".$record['mode']."</td>";
						$table .= "<td>".$record['qsl_rcvd']."</td>";
						$table .= "<td>".$qsl_date."</td>";
						$table .= "<td>QSO Record: ".$status."</td>";
						$table .= "<td>LoTW Record: ".$lotw_status."</td>";
					$table .= "<tr>";
				};
			
				$table .= "</table>";

				unlink('./uploads/'.$data['upload_data']['file_name']);

				$data['lotw_table'] = $table;
			
				$data['page_title'] = "LoTW ADIF Information";
				$this->load->view('layout/header', $data);
				$this->load->view('lotw/analysis');
				$this->load->view('layout/footer');
			}

		}
	}


}