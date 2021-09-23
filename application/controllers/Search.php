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
    }

	public function index()
	{
		$data['page_title'] = "Search";

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

    function json_result() {
          if(isset($_POST['search'])) {
			  $result = $this->fetchQueryResult($_POST['search']);
			  echo json_encode($result->result_array());
		  } else {
				echo "Noooooooob";
          }
    }

	function export_to_adif() {
		if(isset($_POST['search'])) {
			$data['qsos'] = $this->fetchQueryResult($_POST['search']);

			$this->load->view('adif/data/exportall', $data);
		} else {
			echo "Noooooooob";
		}
	}

	function fetchQueryResult($json) {

		$search_items = json_decode($json, true);

		$search_type = "";

		foreach($search_items as $key=>$value){


			if($value == "AND") {
				$search_type = "AND";
			}
			if ($value == "OR") {
				$search_type = "OR";
			}

			if(is_array($value)) {
				foreach($value as $values)
				{

					if(isset($values['rules'])) {
						if($values['condition'] == "AND") {
							$this->db->group_start();
						} else {
							$this->db->or_group_start();
						}
						foreach($values['rules'] as $group_value)
						{
							if($group_value['operator'] == "equal") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'], $group_value['value']);
								} else {
									$this->db->or_where($group_value['field'], $group_value['value']);
								}
							}

							if($group_value['operator'] == "not_equal") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' !=', $group_value['value']);
								} else {
									$this->db->or_where($group_value['field'].' !=', $group_value['value']);
								}
							}

							if($group_value['operator'] == "begins_with") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' like ', $group_value['value']."%");
								} else {
									$this->db->or_where($group_value['field'].' like ', $group_value['value']."%");
								}
							}

							if($group_value['operator'] == "contains") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' like ', "%".$group_value['value']."%");
								} else {
									$this->db->or_where($group_value['field'].' like ', "%".$group_value['value']."%");
								}
							}

							if($group_value['operator'] == "ends_with") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' like ', "%".$group_value['value']);
								} else {
									$this->db->or_where($group_value['field'].' like ', "%".$group_value['value']);
								}
							}

							if($group_value['operator'] == "is_empty") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'], "''");
								} else {
									$this->db->or_where($group_value['field'], "''");
								}
							}

							if($group_value['operator'] == "is_not_empty") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' !=', "''");
								} else {
									$this->db->or_where($group_value['field'].' !=', "''");
								}
							}

							if($group_value['operator'] == "is_null") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' is ', NULL);
								} else {
									$this->db->or_where($group_value['field'].' is ', NULL);
								}
							}

							if($group_value['operator'] == "is_not_null") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' is not ', NULL);
								} else {
									$this->db->or_where($group_value['field'].' is not ', NULL);
								}
							}


							if($group_value['operator'] == "less") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' <', $group_value['value']);
								} else {
									$this->db->or_where($group_value['field'].' <', $group_value['value']);
								}
							}

							if($group_value['operator'] == "less_or_equal") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' <=', $group_value['value']);
								} else {
									$this->db->or_where($group_value['field'].' <=', $group_value['value']);
								}
							}

							if($group_value['operator'] == "greater") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' >', $group_value['value']);
								} else {
									$this->db->or_where($group_value['field'].' >', $group_value['value']);
								}
							}

							if($group_value['operator'] == "greater_or_equal") {
								if($values['condition'] == "AND") {
									$this->db->where($group_value['field'].' >=', $group_value['value']);
								} else {
									$this->db->or_where($group_value['field'].' >=', $group_value['value']);
								}
							}

						}
						$this->db->group_end();
					} else {
						//print_r($values['field']);
						if(isset($values['operator'])) {

						}
						if($values['operator'] == "equal") {
							if($search_type == "AND") {
								$this->db->where($values['field'], $values['value']);
							} else {
								$this->db->or_where($values['field'], $values['value']);
							}
						}

						if($values['operator'] == "not_equal") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' !=', $values['value']);
							} else {
								$this->db->or_where($values['field'].' !=', $values['value']);
							}
						}

						if($values['operator'] == "begins_with") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' like ', $values['value']."%");
							} else {
								$this->db->or_where($values['field'].' like ', $values['value']."%");
							}
						}

						if($values['operator'] == "contains") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' like ', "%".$values['value']."%");
							} else {
								$this->db->or_where($values['field'].' like ', "%".$values['value']."%");
							}
						}

						if($values['operator'] == "ends_with") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' like ', "%".$values['value']);
							} else {
								$this->db->or_where($values['field'].' like ', "%".$values['value']);
							}
						}

						if($values['operator'] == "is_empty") {
							if($search_type == "AND") {
								$this->db->where($values['field'], "");
							} else {
								$this->db->or_where($values['field'], "");
							}
						}

						if($values['operator'] == "is_not_empty") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' !=', "");
							} else {
								$this->db->or_where($values['field'].' !=', "");
							}
						}

						if($values['operator'] == "is_null") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' is ', NULL);
							} else {
								$this->db->or_where($values['field'].' is ', NULL);
							}
						}

						if($values['operator'] == "is_not_null") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' is not ', NULL);
							} else {
								$this->db->or_where($values['field'].' is not ', NULL);
							}
						}

						if($values['operator'] == "less") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' <', $values['value']);
							} else {
								$this->db->or_where($values['field'].' <', $values['value']);
							}
						}

						if($values['operator'] == "less_or_equal") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' <=', $values['value']);
							} else {
								$this->db->or_where($values['field'].' <=', $values['value']);
							}
						}

						if($values['operator'] == "greater") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' >', $values['value']);
							} else {
								$this->db->or_where($values['field'].' >', $values['value']);
							}
						}

						if($values['operator'] == "greater_or_equal") {
							if($search_type == "AND") {
								$this->db->where($values['field'].' >=', $values['value']);
							} else {
								$this->db->or_where($values['field'].' >=', $values['value']);
							}
						}
					}

				}
			}
		}
		$this->db->order_by('COL_TIME_ON', 'DESC');
		$this->db->join('station_profile', 'station_profile.station_id = '.$this->config->item('table_name').'.station_id');
		$query = $this->db->get($this->config->item('table_name'));
		return $query;
	}
}
