<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class adif extends CI_Controller {

	/* Controls ADIF Import/Export Functions */

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));

		$this->load->model('user_model');
		if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
	}

	public function test() {
		if(validateADIFDate('20120228') == true){
			echo "valid date";
		} else {
			echo "date incorrect";
		}


	}

	/* Shows Export Views */
	public function export() {

		$data['page_title'] = "ADIF Export";


		$this->load->view('interface_assets/header', $data);
		$this->load->view('adif/main');
		$this->load->view('interface_assets/footer');
	}

	// Export all QSO Data in ASC Order of Date.
	public function exportall()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_all();


		$this->load->view('adif/data/exportall', $data);
	}


	// Export all QSO Data in ASC Order of Date.
	public function exportsat()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->sat_all();

		$this->load->view('adif/data/exportsat', $data);
	}

	// Export all QSO Data in ASC Order of Date.
	public function exportsatlotw()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->satellte_lotw();

		$this->load->view('adif/data/exportsat', $data);
	}

	public function export_custom() {
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$station_id = $this->security->xss_clean($this->input->post('station_profile'));

		// Used for exporting QSOs not previously exported to LoTW
        if ($this->input->post('exportLotw') == 1) {
            $exportLotw = true;
        } else {
            $exportLotw = false;
		}

		$data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'), $station_id, $exportLotw);


		$this->load->view('adif/data/exportall', $data);


		if ($this->input->post('markLotw') == 1) {
            foreach ($data['qsos']->result() as $qso)
            {
                $this->adif_data->mark_lotw_sent($qso->COL_PRIMARY_KEY);
            }
        }
    }

    public function mark_lotw() {
        // Set memory limit to unlimited to allow heavy usage
        ini_set('memory_limit', '-1');

		$station_id = $this->security->xss_clean($this->input->post('station_profile'));
        $this->load->model('adif_data');

        $data['qsos'] = $this->adif_data->export_custom($this->input->post('from'), $this->input->post('to'), $station_id);

        foreach ($data['qsos']->result() as $qso)
        {
            $this->adif_data->mark_lotw_sent($qso->COL_PRIMARY_KEY);
        }

        $this->load->view('adif/mark_lotw', $data);
    }

	public function export_lotw()
	{
		// Set memory limit to unlimited to allow heavy usage
		ini_set('memory_limit', '-1');

		$this->load->model('adif_data');

		$data['qsos'] = $this->adif_data->export_lotw();

		$this->load->view('adif/data/exportall', $data);

		foreach ($data['qsos']->result() as $qso)
		{
			$this->adif_data->mark_lotw_sent($qso->COL_PRIMARY_KEY);
		}
	}

	public function index() {
		$this->load->model('stations');

		$data['page_title'] = "ADIF Import / Export";
		$data['max_upload'] = ini_get('upload_max_filesize');

		$data['station_profile'] = $this->stations->all_of_user();
        $active_station_id = $this->stations->find_active();
        $station_profile = $this->stations->profile($active_station_id);

		$data['active_station_info'] = $station_profile->row();

		$this->load->view('interface_assets/header', $data);
		$this->load->view('adif/import');
		$this->load->view('interface_assets/footer');
	}

	public function import() {
		$this->load->model('stations');
		$data['station_profile'] = $this->stations->all_of_user();

        $active_station_id = $this->stations->find_active();
        $station_profile = $this->stations->profile($active_station_id);

		$data['active_station_info'] = $station_profile->row();

		$data['page_title'] = "ADIF Import";

		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'adi|ADI|adif|ADIF';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$data['error'] = $this->upload->display_errors();

			$data['max_upload'] = ini_get('upload_max_filesize');

			$this->load->view('interface_assets/header', $data);
			$this->load->view('adif/import');
			$this->load->view('interface_assets/footer');
		}
		else
		{

			$data = array('upload_data' => $this->upload->data());

			ini_set('memory_limit', '-1');
			set_time_limit(0);

			$this->load->model('logbook_model');

			$this->load->library('adif_parser');

			$this->adif_parser->load_from_file('./uploads/'.$data['upload_data']['file_name']);

			$this->adif_parser->initialize();
			$custom_errors = "";
			while($record = $this->adif_parser->get_record())
			{
				if(count($record) == 0)
				{
					break;
				};


				$custom_errors .= $this->logbook_model->import($record, $this->input->post('station_profile'),
					$this->input->post('skipDuplicate'), $this->input->post('markLotw'), $this->input->post('dxccAdif'), $this->input->post('markQrz'), true, $this->input->post('operatorName'));

			};

			$data['adif_errors'] = $custom_errors;

			unlink('./uploads/'.$data['upload_data']['file_name']);

			$data['page_title'] = "ADIF Imported";
			$this->load->view('interface_assets/header', $data);
			$this->load->view('adif/import_success');
			$this->load->view('interface_assets/footer');

		}
	}
}

/* End of file adif.php */
