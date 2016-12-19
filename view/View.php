<?php

class View {
    private $tplMaster;
    private $tplMasterConteudo;
        
    function __construct() {
        $this->tplMaster = TPLPATH . "/Template.html";
    }
    
    public function definirScript($scripts){
        $scriptPath = "../js";
        $paths = '';
        if(is_array($scripts)){
            foreach($scripts as $s){
                $paths .= "<script src='{$scriptPath}/{$s}.js'></script>";
            }
        }else{
            $paths = "<script src='{$scriptPath}/{$scripts}.js'></script>";
        }
           
        $this->replace(array('scripts'=>$paths));            
    }
    
    public function definirCSS($css){
        $scriptPath = "../css";
        $paths = '';
        if(is_array($css)){
            foreach($css as $c){
                $paths .= "<link rel='stylesheet' type='text/css' href='{$scriptPath}/{$c}.css'>";
            }
        }else{
            $paths .= "<link rel='stylesheet' type='text/css' href='{$scriptPath}/{$css}.css'>";
        }
           
        $this->replace(array('CSS'=>$paths));            
    }
    
    public function definirConteudo($tplConteudo){
        $Conteudo = file_get_contents(TPLPATH . "/$tplConteudo.html");
        $this->tplMasterConteudo = file_get_contents($this->tplMaster);        
        $this->replace(array('CONTEUDO'=>$Conteudo));
    }
    
    public function mostrar(){
        $this->tplMasterConteudo = preg_replace('/\{.*\}/', '',  $this->tplMasterConteudo);
        echo $this->tplMasterConteudo;        
    }
    
    public function replace($valores){
        foreach($valores as $ph=>$vl){           
            $this->tplMasterConteudo = str_replace("{{$ph}}", $vl, $this->tplMasterConteudo);            
        }
    }
}
