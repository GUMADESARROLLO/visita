<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class visitas_controller extends CI_Controller {
	
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
		$this->load->view('Visitas/visitas', $data);
		$this->load->view('Footer/footer');
		$this->load->view('JsView/js_visitas');
    }

    public function returnDataVisita() {
    	$this->visitas_model->returnDataVisita($this->input->post('F1'), $this->input->post('F2'));
    }

    public function returnDetalleVisita() {
    	$this->pedidos_model->returnDetalleVisita($this->input->post('idReporte'), $this->input->post('idMedico'));
    }

    public function descargarExcelVisitas($F1, $F2, $ruta) {
    	$this->visitas_model->descargarExcelVisitas($F1,$F2,$ruta);	
    }
}