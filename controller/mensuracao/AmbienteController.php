<?php

require_once(CTRLPATH . "/GECELController.php");
require_once(LIBPATH . '/GerenciadorLeitura/Gerenciador.php');


class AmbienteController extends GECELController{
    private $gerenciador;
    
    public function __construct() {
        ;
    }
    
    public function registrar($args){
        $this->gerenciador = new Gerenciador();
        $this->gerenciador->registrar($args, $_SERVER['REMOTE_ADDR']);
        echo json_encode(array('ID'=>1,'Msg'=>'Registro efetuado'));
    }
    
    
    
}