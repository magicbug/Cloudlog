<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {


    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        if($this->config->item('public_search') != TRUE) {
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
            $json = $_POST['search'];

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
                        //print_r($values['field']);
                        if($values['operator'] == "equal") {
                            if($search_type == "AND") {
                                $this->db->where($values['field'], $values['value']); 
                            } else {
                                $this->db->or_where($values['field'], $values['value']); 
                            }
                        }

                        if($values['operator'] == "not_equal") {
                            if($search_type == "AND") {
                               $this->db->where($values['field'].' !=', $name);
                            } else {
                               $this->db->or_where($values['field'].' !=', $name);
                            }
                        }

                        if($values['operator'] == "less") {
                            if($search_type == "AND") {
                               $this->db->where($values['field'].' <', $name);
                            } else {
                               $this->db->or_where($values['field'].' <', $name);
                            }
                        }

                        if($values['operator'] == "less_or_equal") {
                            if($search_type == "AND") {
                               $this->db->where($values['field'].' <=', $name);
                            } else {
                               $this->db->or_where($values['field'].' <=', $name);
                            }
                        }

                        if($values['operator'] == "greater") {
                            if($search_type == "AND") {
                               $this->db->where($values['field'].' >', $name);
                            } else {
                               $this->db->or_where($values['field'].' >', $name);
                            }
                        }

                        if($values['operator'] == "greater_or_equal") {
                            if($search_type == "AND") {
                               $this->db->where($values['field'].' >=', $name);
                            } else {
                               $this->db->or_where($values['field'].' >=', $name);
                            }
                        }

                    }
                }
            }
            $this->db->order_by('COL_TIME_ON', 'DESC');
            $query = $this->db->get($this->config->item('table_name'));

            echo json_encode($query->result_array());
          } else {
            echo "Noooooooob";
          }

    }
}
