<?php class Migrate extends CI_Controller {

  public function index()
  {
    $this->load->library('Migration');

    if ( ! $this->migration->latest()) {
      show_error($this->migration->error_string());
    }
  }

} ?>
