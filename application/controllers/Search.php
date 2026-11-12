<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {


    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        if($this->optionslib->get_option('global_search') != "true") {
            $this->load->model('user_model');
            if(!$this->user_model->authorize(2)) { $this->session->set_flashdata('notice', 'You\'re not allowed to do that!'); redirect('dashboard'); }
        }

        // Load language files
        $this->lang->load(array(
          'lotw',
          'eqsl',
          'search',
        ));
    }

    private function require_search_write_access()
    {
        $this->load->model('user_model');
        if (!$this->user_model->authorize(2)) {
            $this->session->set_flashdata('notice', 'You\'re not allowed to do that!');
            redirect('dashboard');
        }
    }

	public function index()
	{
		$postedCallsign = trim((string) $this->security->xss_clean($this->input->post('callsign')));
		if ($postedCallsign !== '') {
			redirect('search?callsign=' . rawurlencode($postedCallsign));
			return;
		}

		$queryCallsign = trim((string) $this->security->xss_clean($this->input->get('callsign')));
		if ($queryCallsign === '') {
			$queryCallsign = trim((string) $this->security->xss_clean($this->input->get('q')));
		}
		$queryExact = $this->input->get('exact') === '1';

		$data['page_title'] = "Search";
		$data['initial_search'] = $queryCallsign;
		$data['initial_exact'] = $queryExact;

        $this->load->view('interface_assets/header', $data);
		$this->load->view('search/main');
        $this->load->view('interface_assets/footer');
	}

    // Filter is for advanced searching and filtering of the logbook
    public function filter() {
        $data['page_title'] = "Search & Filter Logbook";

        $this->load->library('form_validation');

        $this->load->model('Search_filter');

        $data['get_table_names'] = $this->Search_filter->get_table_columns();
		$data['stored_queries'] = $this->Search_filter->get_stored_queries();

        //print_r($this->Search_filter->get_table_columns());

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('interface_assets/header', $data);
            $this->load->view('search/filter');
            $this->load->view('interface_assets/footer');
        }
        else
        {
            $this->load->view('interface_assets/header', $data);
            $this->load->view('search/filter');
            $this->load->view('interface_assets/footer');
        }
    }

	// Searches for duplicate QSOs within 30m of time difference
    public function duplicates() {
		$this->load->model('stations');

		$data['station_profile'] = $this->stations->all_of_user();
        $data['page_title'] = "Duplicate QSOs within 30m time difference";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('search/duplicates');
		$this->load->view('interface_assets/footer');
    }

    public function merge()
    {
        $this->require_search_write_access();

        $ids_raw = $this->security->xss_clean($this->input->get('ids'));
        $ids = array_filter(array_map('intval', explode(',', (string) $ids_raw)));

        $this->load->model('Qso_merge_model', 'qso_merge_model');
        $this->load->library('Qso_merge_field_chooser', null, 'merge_chooser');

        $loaded = $this->qso_merge_model->load_merge_group($ids);
        if (!$loaded['ok']) {
            $this->session->set_flashdata('error', $loaded['message']);
            redirect('search/duplicates');
        }

        $qsos = $loaded['qsos'];
        $default_keeper = $this->merge_chooser->suggest_keeper_id($qsos);
        $default_choices = $this->merge_chooser->default_choices($qsos);
        $fields = $this->merge_chooser->mergeable_fields($qsos);

        $data['page_title'] = lang('search_merge_title');
        $data['qsos'] = $qsos;
        $data['fields'] = $fields;
        $data['default_keeper'] = $default_keeper;
        $data['default_choices'] = $default_choices;
        $data['qso_ids'] = implode(',', array_keys($qsos));

        $this->load->view('interface_assets/header', $data);
        $this->load->view('search/merge');
        $this->load->view('interface_assets/footer');
    }

    public function merge_preview()
    {
        $this->require_search_write_access();
        header('Content-Type: application/json');

        $ids = array_filter(array_map('intval', explode(',', (string) $this->input->post('ids'))));
        $keeper_id = (int) $this->input->post('keeper_id');
        $field_sources = $this->input->post('field_sources');
        if (!is_array($field_sources)) {
            $field_sources = [];
        }

        $this->load->model('Qso_merge_model', 'qso_merge_model');
        $loaded = $this->qso_merge_model->load_merge_group($ids);
        if (!$loaded['ok']) {
            echo json_encode(['ok' => false, 'message' => $loaded['message']]);
            return;
        }

        if (!isset($loaded['qsos'][$keeper_id])) {
            echo json_encode(['ok' => false, 'message' => 'Invalid keeper QSO.']);
            return;
        }

        $preview = $this->qso_merge_model->build_merged_preview($keeper_id, $loaded['qsos'], $field_sources);
        echo json_encode(['ok' => true, 'preview' => $preview]);
    }

    public function merge_confirm()
    {
        $this->require_search_write_access();
        header('Content-Type: application/json');

        $ids = array_filter(array_map('intval', explode(',', (string) $this->input->post('ids'))));
        $keeper_id = (int) $this->input->post('keeper_id');
        $field_sources = $this->input->post('field_sources');
        if (!is_array($field_sources)) {
            $field_sources = [];
        }

        $this->load->model('Qso_merge_model', 'qso_merge_model');
        $delete_ids = array_values(array_diff($ids, [$keeper_id]));
        $result = $this->qso_merge_model->execute_merge($keeper_id, $delete_ids, $field_sources);

        if (!$result['ok']) {
            echo json_encode($result);
            return;
        }

        echo json_encode([
            'ok' => true,
            'keeper_id' => $result['keeper_id'],
            'redirect' => site_url('logbook/view/' . $result['keeper_id']),
        ]);
    }

	// Searches for incorrect CQ Zones
	public function incorrect_cq_zones() {
		$this->load->model('stations');

		$data['station_profile'] = $this->stations->all_of_user();
		$data['page_title'] = "Incorrectly logged CQ zones";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('search/cqzones');
		$this->load->view('interface_assets/footer');
	}

	// Searches for unconfirmed Lotw QSOs where QSO partner has uploaded to LoTW after the QSO date
	public function lotw_unconfirmed() {
		$this->load->model('stations');

		$data['station_profile'] = $this->stations->all_of_user();
		$data['page_title'] = "QSOs unconfirmed on LoTW, but the callsign has uploaded to LoTW after QSO date";

		$this->load->view('interface_assets/header', $data);
		$this->load->view('search/lotw_unconfirmed');
		$this->load->view('interface_assets/footer');
	}

    function json_result() {
          if(isset($_POST['search'])) {
			  $result = $this->fetchQueryResult($_POST['search'], false);
			  echo json_encode($result->result_array());
		  }
    }

	function get_stored_queries() {
		$this->load->model('Search_filter');
		$data['result'] = $this->Search_filter->get_stored_queries();
		$this->load->view('search/stored_queries', $data);
	}

	function search_result() {
		if(isset($_POST['search'])) {
			$data['results'] = $this->fetchQueryResult($_POST['search'], false);
			$this->load->view('search/search_result_ajax', $data);
		}
	}

	function export_to_adif() {
		if(isset($_POST['search'])) {
			$data['qsos'] = $this->fetchQueryResult($_POST['search'], false);
			$this->load->view('adif/data/exportall', $data);
		}
	}

	function export_stored_query_to_adif() {
		$this->db->where('id', xss_clean($this->input->post('id')));
		$sql = $this->db->get('queries')->result();

		$data['qsos'] = $this->db->query($sql[0]->query);
		$this->load->view('adif/data/exportall', $data);
	}

	function run_query() {
		$this->db->where('id', xss_clean($this->input->post('id')));
		$sql = $this->db->get('queries')->result();
		$sql = $sql[0]->query;

		if (stristr($sql, 'select') && !stristr($sql, 'delete') && !stristr($sql, 'update')) {
			$data['results'] = $this->db->query($sql);

			$this->load->view('search/search_result_ajax', $data);
		}
	}

	function save_query() {
		if(isset($_POST['search'])) {
			$query = $this->fetchQueryResult($_POST['search'], true);

			$data = array(
				'userid' => xss_clean($this->session->userdata('user_id')),
				'query' => $query,
				'description' => xss_clean($_POST['description'])
			);

			$this->db->insert('queries', $data);
			$last_id = $this->db->insert_id();
			header('Content-Type: application/json');
			echo json_encode(array('id' => $last_id, 'description' => xss_clean($_POST['description'])));
		}
	}

	function delete_query() {
		$id = xss_clean($this->input->post('id'));
		$this->load->model('search_filter');
		$this->search_filter->delete_query($id);
	}

	function save_edited_query() {
		$data = array(
			'description' => xss_clean($this->input->post('description')),
		);

		$this->db->where('id', xss_clean($this->input->post('id')));
		$this->db->where('userid', $this->session->userdata['user_id']);
		$this->db->update('queries', $data);
	}

	function buildWhere(array $object, string $condition = null): void
	{
		/*
		 * The $object is one of the following:
		 * - a group, with 'condition' and 'rules' keys
		 * - a condition, that is either 'AND' or 'OR' depending on the parent group setting
		 */
		$objectIsGroup = isset($object['condition']);
		if ($objectIsGroup) {
			if ($condition === null || $condition === 'AND') {
				$this->db->group_start();
			} else {
				$this->db->or_group_start();
			}
			foreach ($object['rules'] as $rule) {
				/*
				 * Now iterate over the children, that are either groups or conditions
				 */
				$this->buildWhere($rule, $object['condition']);
			}
			$this->db->group_end();
		} else {
			$object['field'] = $this->config->item('table_name') . '.' . $object['field'];

			if ($object['operator'] == "equal") {
				if ($condition == "AND") {
					$this->db->where($object['field'], $object['value']);
				} else {
					$this->db->or_where($object['field'], $object['value']);
				}
			}

			if ($object['operator'] == "not_equal") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' !=', $object['value']);
				} else {
					$this->db->or_where($object['field'] . ' !=', $object['value']);
				}
			}

			if ($object['operator'] == "begins_with") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' like ', $object['value'] . "%");
				} else {
					$this->db->or_where($object['field'] . ' like ', $object['value'] . "%");
				}
			}

			if ($object['operator'] == "contains") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' like ', "%" . $object['value'] . "%");
				} else {
					$this->db->or_where($object['field'] . ' like ', "%" . $object['value'] . "%");
				}
			}

			if ($object['operator'] == "ends_with") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' like ', "%" . $object['value']);
				} else {
					$this->db->or_where($object['field'] . ' like ', "%" . $object['value']);
				}
			}

			if ($object['operator'] == "is_empty") {
				if ($condition == "AND") {
					$this->db->where($object['field'], '');
				} else {
					$this->db->or_where($object['field'], '');
				}
			}

			if ($object['operator'] == "is_not_empty") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' !=', '');
				} else {
					$this->db->or_where($object['field'] . ' !=', '');
				}
			}

			if ($object['operator'] == "is_null") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' IS NULL');
				} else {
					$this->db->or_where($object['field'] . ' IS NULL');
				}
			}

			if ($object['operator'] == "is_not_null") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' IS NOT NULL');
				} else {
					$this->db->or_where($object['field'] . ' IS NOT NULL');
				}
			}


			if ($object['operator'] == "less") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' <', $object['value']);
				} else {
					$this->db->or_where($object['field'] . ' <', $object['value']);
				}
			}

			if ($object['operator'] == "less_or_equal") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' <=', $object['value']);
				} else {
					$this->db->or_where($object['field'] . ' <=', $object['value']);
				}
			}

			if ($object['operator'] == "greater") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' >', $object['value']);
				} else {
					$this->db->or_where($object['field'] . ' >', $object['value']);
				}
			}

			if ($object['operator'] == "greater_or_equal") {
				if ($condition == "AND") {
					$this->db->where($object['field'] . ' >=', $object['value']);
				} else {
					$this->db->or_where($object['field'] . ' >=', $object['value']);
				}
			}
		}
	}

	function fetchQueryResult($json, $returnquery) {
		$search_items = json_decode($json, true);

		$this->db->select($this->config->item('table_name').'.*, station_profile.*, dxcc_entities.name as station_country, dxcc_entities.lat, dxcc_entities.`long`', false);

		$this->db->group_start();
		$this->buildWhere($search_items);
		$this->db->group_end();

		$this->db->order_by('COL_TIME_ON', 'DESC');
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$this->db->join('dxcc_entities', $this->config->item('table_name').'.col_dxcc = dxcc_entities.adif', 'left');
		$this->db->where('station_profile.user_id', $this->session->userdata('user_id'));

		if ($returnquery) {
			$query = $this->db->get_compiled_select($this->config->item('table_name'));
		} else {
			$query = $this->db->get($this->config->item('table_name'));
		}
		return $query;
	}
}
