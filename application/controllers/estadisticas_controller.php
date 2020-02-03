<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class estadisticas_controller extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');

		if($this->session->userdata('logged')==0) {
			redirect(base_url().'index.php/login','refresh');
		}
	}

    public function index() {
        $data['rutas'] = $this->estadistica_model->returnRutas();
		$this->load->view('Header/header');
		$this->load->view('Estadisticas/estadisticas', $data);
		$this->load->view('Footer/footer');
		$this->load->view('JsView/js_estadistica');
    }

    public function returnDataMeses($f1, $f2, $rta) {
    	$this->estadistica_model->dataVentas($f1, $f2, $rta);
    }
}