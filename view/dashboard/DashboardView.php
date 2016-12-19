<?php
require_once(VIEWPATH . "/View.php");
class DashboardView extends View{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function Principal(){
        $this->definirConteudo("dashboard/dashboardTemplate");      
        $this->definirScript(array('dashboard/dashboard','HistoGram','LineChart'));
        $this->definirCSS(array('dashboard/dashboard'));
    }
}