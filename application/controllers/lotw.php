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
	
	private function loadFromFile($filepath)
	{
		// Figure out how we should be marking QSLs confirmed via LoTW
		$query = $query = $this->db->query('SELECT lotw_rcvd_mark FROM config');
		$q = $query->row();
		$config['lotw_rcvd_mark'] = $q->lotw_rcvd_mark;
	
		ini_set('memory_limit', '-1');
		set_time_limit(0);

		$this->load->library('adif_parser');

		$this->adif_parser->load_from_file($filepath);

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
			
			// If we have a positive match from LoTW, record it in the DB according to the user's preferences
			if ($record['qsl_rcvd'] == "Y")
			{
				$record['qsl_rcvd'] = $config['lotw_rcvd_mark'];
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

		unlink($filepath);

		$data['lotw_table'] = $table;

		$data['page_title'] = "LoTW ADIF Information";
		$this->load->view('layout/header', $data);
		$this->load->view('lotw/analysis');
		$this->load->view('layout/footer');
	}

	public function import() {	
		$data['page_title'] = "LoTW ADIF Import";

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI';
		
		$this->load->library('upload', $config);
		
		$this->load->model('logbook_model');
		
		if ($this->input->post('lotwimport') == 'fetch')
		{			
			$file = $config['upload_path'] . 'lotwreport_download.adi';
			
			// Get credentials for LoTW
			$query = $this->user_model->get_by_id($this->session->userdata('user_id'));
    		$q = $query->row();
    		$data['user_lotw_name'] = $q->user_lotw_name;
			$data['user_lotw_password'] = $q->user_lotw_password;
			
			// Get URL for downloading LoTW
			$query = $query = $this->db->query('SELECT lotw_download_url FROM config');
			$q = $query->row();
			$lotw_url = $q->lotw_download_url;
			
			// Validate that LoTW credentials are not empty
			// TODO: We don't actually see the error message
			if ($data['user_lotw_name'] == '' || $data['user_lotw_password'] == '')
			{
				$this->session->set_flashdata('warning', 'You have not defined your ARRL LoTW credentials!'); redirect('dashboard');
			}
			
			// Query the logbook to determine when the last LoTW confirmation was
			$lotw_last_qsl_date = $this->logbook_model->lotw_last_qsl_date();
						
			// TODO: Consolidate code
			// TODO: Specifiy in config file whether we want LoTW confirms as V or Y. Both are acceptable under ADIF specification. HRD seems to use V. Everyone else that I've used uses Y.
			
			// Build URL for LoTW report file
			$lotw_url .= "?";
			$lotw_url .= "login=" . $data['user_lotw_name'];
			$lotw_url .= "&password=" . $data['user_lotw_password'];
			$lotw_url .= "&qso_query=1&qso_qsl='yes'";
			
			//TODO: Option to specifiy whether we download location data from LoTW or not
			//$lotw_url .= "&qso_qsldetail=\"yes\";
			
			$lotw_url .= "&qso_qslsince=";
			$lotw_url .= "$lotw_last_qsl_date";
			
			// Only pull back entries that belong to this callsign
			$lotw_call = $this->session->userdata('user_callsign');
			$lotw_url .= "&qso_owncall=$lotw_call";
			
			file_put_contents($file, file_get_contents($lotw_url));
			
			ini_set('memory_limit', '-1');
			$this->loadFromFile($file);
		}
		else
		{
			if ( ! $this->upload->do_upload())
			{
			
				$data['error'] = $this->upload->display_errors();

				$this->load->view('layout/header', $data);
				$this->load->view('lotw/import');
				$this->load->view('layout/footer');
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
		
				$this->loadFromFile('./uploads/'.$data['upload_data']['file_name']);
			}
		}
	} // end function
} // end class