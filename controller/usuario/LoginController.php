<?php
require_once(CTRLPATH . "/GECELController.php");
require_once(VIEWPATH . "/usuario/LoginView.php");
require_once(DAOPATH . "/auth/LoginDAO.php");

class LoginController extends GECELController{
    private $view;
    private $LoginDAO;
    
    public function __construct() {
        parent::__construct();
        $this->view = new LoginView();
        $this->LoginDAO = new LoginDAO();
    }
    
    public function Principal(){
        $this->view->Principal();        
        $this->view->mostrar();        
    }
    
    public function autenticar($args){
        $this->LoginDAO->usuario = $args['usuario'];
        $this->LoginDAO->senha = $args['senha'];
        echo json_encode($this->LoginDAO->autenticar());
    }
    
}

