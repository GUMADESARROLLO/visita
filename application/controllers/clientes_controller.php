<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class clientes_controller extends CI_Controller {
	
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
		$this->load->view('Clientes/clientes', $data);
		$this->load->view('Footer/footer');
		$this->load->view('JsView/js_clientes');
    }
    public function returnDataClientes() {
        $this->clientes_model->returnDataPedido();
    }
    public function returnHistoricoCliente() {
        $this->clientes_model->returnHistoricoCliente($this->input->post('idCliente'));
    }
    public function clientes_perfil($id){
        $data['AllData'] = $this->clientes_model->InformacionCliente($id);
        $this->load->view('Header/header');
        $this->load->view('Clientes/clientes_perfil', $data);
        $this->load->view('Footer/footer');
        $this->load->view('JsView/js_clientes_perfil');
    }
}