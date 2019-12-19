<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home_controller extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('session');

		if($this->session->userdata('logged')==0) {
			redirect(base_url().'index.php/login','refresh');
		}
	}

    public function index() {
        $data['rutas'] = $this->home_model->returnRutas();
		$this->load->view('Header/header');
		$this->load->view('Home/home', $data);
		$this->load->view('Footer/footer');
		$this->load->view('JsView/js_home');
    }

    public function returnDataVisita() {
    	$this->home_model->returnDataVisita($this->input->post('F1'), $this->input->post('F2'));
    }

    public function returnDataMedico() {
    	$this->home_model->returnDataMedico($this->input->post('R'));
    }

    public function returnDetalleVisita() {
    	$this->home_model->returnDetalleVisita($this->input->post('idReporte'), $this->input->post('idMedico'));
    }

    public function returnDetalleMedico() {
    	$this->home_model->returnDetalleMedico($this->input->post('idMedico'));
    }
}
