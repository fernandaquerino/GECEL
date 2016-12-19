<?php

require_once(CTRLPATH . "/GECELController.php");
require_once(LIBPATH . '/GerenciadorLeitura/Gerenciador.php');
require_once(VIEWPATH . "/dashboard/DashboardView.php");
require_once(DAOPATH . "/mensuracao/AmbienteDAO.php");

class DashboardController extends GECELController{
    private $view;
    private $dao;
    
    public function __construct() {
        parent::__construct();
        $this->view = new DashboardView();
        $this->dao = new AmbienteDAO();
    }
    
    public function Principal(){
        $this->view->Principal();    
        $this->view->mostrar();        
    }
    
    public function obterConsumoAno($args){        
        echo json_encode($this->dao->obterConsumoAno());
    }
    
    public function obterConsumoMes($args){     
        echo json_encode($this->dao->obterConsumoMes($args));
    }
    
    public function obterConsumoMesDia($args){
        echo json_encode($this->dao->obterConsumoMesDia($args));
    }
    
    public function obterConsumoDia($args){
        echo json_encode($this->dao->obterConsumoDia($args));
    }
    
}