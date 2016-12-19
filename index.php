<?php

require_once('config.php');

if (IS_DEBUG_ENABLED) {
    @ini_set('display_errors', 1);
    @error_reporting(E_ALL);
}

//se o script foi chamado via terminal
if(isset($argv[1]) && $argv[1] == 'gravar' ){
    try{
        require_once(LIBPATH . "/GerenciadorLeitura/Gerenciador.php");
        $obj = new Gerenciador();
        $obj->gravarLeitura();
        exit();
    }catch(Exception $e){
        file_put_contents('/var/www/html/GECEL/tmp/erros.log', date('d/m/Y H:i:s ') . $e->getMessage(), FILE_APPEND);        
    }
}

@session_start();
$URL = explode('/', str_replace(array(SERVERPATH, 'index.php/'), '', $_SERVER['REQUEST_URI']));

if (empty($URL[1])) {
    header('Location: ' . SERVERPATH . INIT_CTRL);    
    exit;
} else {
    $controllerPath = CTRLPATH . '/' . $URL[0] . '/' . $URL[1] . 'Controller.php';    
}   

if (file_exists($controllerPath)) {
    try {
        require_once("$controllerPath");
    	$controller = "$URL[1]Controller";
        $instancia = new $controller();

        if (isset($URL[2]) && !empty($URL[2])) {
            $metodo = $URL[2];
        } else {
            $metodo = 'Principal';
        }

        if (isset($URL[3])) {
            for ($i = 3; $i < count($URL); $i++) {
                $args[$i - 3] = urldecode($URL[$i]);
            }
            $instancia->$metodo($args);
        } else if (!empty($_POST)) {
            $instancia->$metodo($_POST);
        } else {            
            $instancia->$metodo();
        }
    } catch (Exception $exception) {        
        echo $exception->getMessage();
    }
}
