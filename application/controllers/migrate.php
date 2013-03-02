<? class Migrate extends CI_Controller {

  public function index()
  {
    $this->load->library('Migration');

    if ( ! $this->migration->current()) {
      show_error($this->migration->error_string());
    }
  }

} ?>
