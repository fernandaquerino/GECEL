<?php
require_once(VIEWPATH . "/View.php");
class LoginView extends View{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function Principal(){
        $this->definirConteudo("usuario/LoginTemplate");
        $this->definirScript('auth/Login');
    }        
}