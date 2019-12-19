<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pedidos_controller extends CI_Controller {
	
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
		$this->load->view('Pedidos/pedidos', $data);
		$this->load->view('Footer/footer');
		$this->load->view('JsView/js_pedidos');
    }

    public function returnDataPedido() {
    	$this->pedidos_model->returnDataPedido($this->input->post('F1'), $this->input->post('F2'));
    }

    public function returnDetallePedido() {
    	$this->pedidos_model->returnDetallePedido($this->input->post('idPedido'));
    }
}