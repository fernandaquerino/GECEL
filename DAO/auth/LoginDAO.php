<?php
require_once(DAOPATH . '/DAOBase.php');

class LoginDAO extends DAOBase{
    public $usuario;
    public $senha;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function autenticar(){
        if($this->usuario == 'usuario' && $this->senha == 'senha')
            return array('ID'=>1,'Msg'=>'autenticado');
        else {
            return array('ID'=>-1,'Msg'=>'Nao autenticado');
            
        }
    }
}


