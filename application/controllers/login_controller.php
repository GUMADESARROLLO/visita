<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class login_controller extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('cookie');
		$this->load->helper("url");
	}

    public function index() {
        $ApplicationVersion = new git_version();
        $data = [
            'appVersion' => $ApplicationVersion::get(),
        ];
        $this->load->view('header/header_login');
		$this->load->view('Login/login',$data);
		$this->load->view('footer/footer_login');
    }

	public function acreditarCuenta() {
        $ApplicationVersion = new git_version();
        $datos = [
            'appVersion' => $ApplicationVersion::get(),
        ];
	    $this->form_validation->set_rules('usuario', 'Usuario', 'required|min_length[3]');
	    $this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[3]');

	    $this->form_validation->set_message('required','El campo %s es obligatorio');

	     if($this->form_validation->run()!=false) {

	        $data['user']=$this->login_model->login(
	            $this->input->get_post('usuario'),
                $this->input->get_post('password'));

	        if ($data['user']==0){
				$datos["mensaje"]="¡Ups... Datos erróneos!";
				$this->load->view('header/header_login');
				$this->load->view('login/login', $datos);
				$this->load->view('footer/footer_login');
	        } else {
	            $sessiondata = array(
	                'idUser'        => $data['user'][0]['idUser'],
	                'usuario'       => $data['user'][0]['Usuario'],
                    'nombre'        => $data['user'][0]['Nombre'],
	                'rol'    		=> $data['user'][0]['Rol'],
	                'logged'        => 1
	            );
	            $this->session->set_userdata($sessiondata);

	            if($this->session->userdata){
	                redirect('home');
	            }
	        }
	     }else{
			$datos["mensaje"]="¡Datos vacíos!";
			$this->load->view('header/header_login');
			$this->load->view('Login/login', $datos);
			$this->load->view('footer/footer_login');
	     }
	}

    public function cerrandoSesion() {
        $this->session->sess_destroy();
        $sessiondata = array('logged' => 0);
        $this->session->unset_userdata($sessiondata);
        redirect(base_url().'index.php');
    }
}
