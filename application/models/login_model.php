<?php 
class login_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login($usuario, $pass) {
        if($usuario != false && $pass != false) {
            $query = $this
                    ->db
                    ->where('Usuario', $usuario)
                    ->where('Password', $pass)
                    ->where('Activo', 1)
                    ->get('usuario');

            if($query->num_rows()>0){
                return $query->result_array();
            }
            return 0;
        }
    }
}