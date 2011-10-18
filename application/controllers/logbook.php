<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logbook extends CI_Controller {

	/*
	
		TODO List:
			- Search Option
	*/

	function index()
	{
        $this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) {
            if($this->user_model->validate_session()) {
                $this->user_model->clear_session();
                show_error('Access denied<p>Click <a href="'.site_url('user/login').'">here</a> to log in as another user', 403);
            } else {
                redirect('user/login');
            }
        }

		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/logbook/index/';
		$config['total_rows'] = $this->db->count_all($this->config->item('table_name'));
		$config['per_page'] = '25';
		$config['num_links'] = 6;
		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';
	
		$this->pagination->initialize($config);
	
		//load the model and get results
		$this->load->model('logbook_model');
		$data['results'] = $this->logbook_model->get_qsos($config['per_page'],$this->uri->segment(3));

	
		// load the view
		$this->load->view('layout/header');
		$this->load->view('view_log/index', $data);
		$this->load->view('layout/footer');
		
	}

	/* Used to generate maps for displaying on /logbook/ */
	function qso_map() {
		$this->load->model('logbook_model');

		$this->load->library('qra');

		$data['qsos'] = $this->logbook_model->get_qsos($this->uri->segment(3),$this->uri->segment(4));

		echo "{\"markers\": [";
		$count = 1;
		foreach ($data['qsos']->result() as $row) {
			//print_r($row);
			if($row->COL_GRIDSQUARE != null) {
				$stn_loc = $this->qra->qra2latlong($row->COL_GRIDSQUARE);
				if($count != 1) {
					echo ",";
				}

				echo "{\"lat\":\"".$stn_loc[0]."\",\"lng\":\"".$stn_loc[1]."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";

				$count++;

			} else {
				$query = $this->db->query('
					SELECT *
					FROM dxcc
					WHERE prefix = SUBSTRING( \''.$row->COL_CALL.'\', 1, LENGTH( prefix ) )
					ORDER BY LENGTH( prefix ) DESC
					LIMIT 1 
				');

				foreach ($query->result() as $dxcc) {
					if($count != 1) {
					echo ",";
						}
					echo "{\"lat\":\"".$dxcc->lat."\",\"lng\":\"".$dxcc->long."\", \"html\":\"Callsign: ".$row->COL_CALL."<br />Date/Time: ".$row->COL_TIME_ON."<br />Band: ".$row->COL_BAND."<br />Mode: ".$row->COL_MODE."\",\"label\":\"".$row->COL_CALL."\"}";
					$count++;
				}
			}

		}
		echo "]";
		echo "}";
	}
	
	function view($id) {
		$this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$this->load->library('qra');

		$this->db->where('COL_PRIMARY_KEY', $id); 
		$data['query'] = $this->db->get($this->config->item('table_name'));
		
		$this->load->view('view_log/qso', $data);
	}
	
	function callsign_qra($qra) {
		$this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$this->load->model('logbook_model');

		echo $this->logbook_model->call_qra($qra);
	}
	
	function callsign_name($callsign) {
		$this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$this->load->model('logbook_model');

		echo $this->logbook_model->call_name($callsign);
	}
	
	function test($callsign) {
		$json = file_get_contents("http://callbytxt.org/db/".$callsign.".json");
		
		$obj = json_decode($json);
		
		$uppercase_name = strtolower($obj->{'calls'}->{'first_name'});
		echo ucwords($uppercase_name);
	}
	
	function partial($id) {
		$this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }
	
		$this->db->like('COL_CALL', $id); 
		$this->db->limit(5);
		$query = $this->db->get($this->config->item('table_name'));
	
		if ($query->num_rows() > 0)
		{
			echo "<table class=\"partial\" width=\"100%\">";
				echo "<tr>";
					echo "<td>Date</td>";
					echo "<td>Callsign</td>";
					echo "<td>RST Sent</td>";
					echo "<td>RST Recv</td>";
					echo "<td>Band</td>";
					echo "<td>Mode</td>";
				echo "</tr>";
			foreach ($query->result() as $row)
			{
				echo "<tr>";
					echo "<td>".$row->COL_TIME_ON."</td>";
					echo "<td>".$row->COL_CALL."</td>";
					echo "<td>".$row->COL_RST_SENT."</td>";
					echo "<td>".$row->COL_RST_RCVD."</td>";
					echo "<td>".$row->COL_BAND."</td>";
					echo "<td>".$row->COL_MODE."</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "Unique Callsign: ".strtoupper($id);
		}
	}
	
	function search_result($id) {
		$this->load->model('user_model');
        if(!$this->user_model->authorize($this->config->item('auth_mode'))) { return; }

		$this->db->like('COL_CALL', $id);
		$this->db->or_like('COL_GRIDSQUARE', $id); 
		$query = $this->db->get($this->config->item('table_name'));

		if ($query->num_rows() > 0)
		{
			echo "<table class=\"partial\" width=\"100%\">";
				echo "<tr>";
					echo "<td>Date</td>";
					echo "<td>Callsign</td>";
					echo "<td>RST Sent</td>";
					echo "<td>RST Recv</td>";
					echo "<td>Band</td>";
					echo "<td>Mode</td>";
					echo "<td></td>";
				echo "</tr>";
			foreach ($query->result() as $row)
			{
				echo "<tr>";
					echo "<td>".$row->COL_TIME_ON."</td>";
					echo "<td>".$row->COL_CALL."</td>";
					echo "<td>".$row->COL_RST_SENT."</td>";
					echo "<td>".$row->COL_RST_RCVD."</td>";
					echo "<td>".$row->COL_BAND."</td>";
					echo "<td>".$row->COL_MODE."</td>";
					echo "<td><a href=\"".site_url('qso/edit')."/".$row->COL_PRIMARY_KEY."\" ><img src=\"".base_url()."/images/application_edit.png\" width=\"16\" height=\"16\" alt=\"Edit\" /></a></td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "Unique Callsign: ".strtoupper($id);
		}
	}
	
	// Find DXCC
	function find_dxcc($callsign) {
			$this->load->model('dxcc');

			$dxccinfo = $this->dxcc->info($callsign); 

			foreach ($dxccinfo->result() as $row)
			{
				echo ucfirst(strtolower($row->name));
			}
	}
	
	function bearing($qra) {
		
			$this->load->library('Qra');
			
			
			if($this->session->userdata('user_locator') != null){
				$mylocator = $this->session->userdata('user_locator');
			} else {
				$mylocator = $this->config->item('locator');
			}

			$bearing = $this->qra->bearing($mylocator, $qra);
	
			echo $bearing;
	} 
}