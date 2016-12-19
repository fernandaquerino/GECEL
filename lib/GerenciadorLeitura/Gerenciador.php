<?php
    require_once('RegistroLeitura.php');
    require_once(DAOPATH . '/mensuracao/AmbienteDAO.php');
    
    class Gerenciador{
           
        /**
         * registra a leitura dos módulos de mensuração em
         * um arquivo de log para posterior gravação no banco de dados
         * @param Array $args -dados enviados pelo módulo de mensuração
         * @param String $modulo -IP do módulo
         */
        public function registrar($args, $modulo){
            $x= $args;

            $obj = new RegistroLeitura();
            $obj->pin1 = $x['pin1'];
            $obj->pin2 = $x['pin2'];
            $obj->pin3 = $x['pin3'];
            $obj->pin4 = $x['pin4'];
            $obj->modulo = $modulo;
            $obj->data = date("Y/m/d");
            $obj->horario = date("H:i:s");

            $s = serialize($obj);
        
            file_put_contents(TMPPATH . "/leituras.log", $s ."#", FILE_APPEND);
        }
        
        /**
         * Retorna dados armazenados no arquivo de log para gravação no BD.
         * @return array
         */
        public function obterLeituras(){            
            require_once(SYSPATH . '/config.php');
            
            $conteudo = @file_get_contents(TMPPATH . "/leituras.log");
            if(!empty($error = error_get_last())){
                file_put_contents(LOGPATH . '/errors.log', print_r(array(date('d/m/Y H:i:s')=>$error), true), FILE_APPEND);
                echo json_encode(array('ID'=>-1, 'Msg'=>'Um erro foi incluído no log'));
                exit();
            }
            
            $objsArr = explode("#", $conteudo);
            
            if(count($objsArr) < 1)
                return array();
            
            array_pop($objsArr);            
            $retorno = array();
            
            foreach($objsArr as $o){
                $obj = unserialize($o);    
                array_push($retorno, $obj);
            }
            
            return $retorno;
        }
        
        /**
         * Grava as leituras do módulo de mensuração no banco de dados
         */
        public function gravarLeitura(){            
            $ambienteDAO = new AmbienteDAO();             
            $leituras = $this->obterLeituras();

            $leitAgrupada = array();

            if(!empty($leituras)){
                foreach($leituras as $l){
                    if(!isset($leitAgrupada[$l->modulo])){
                        $leitAgrupada[$l->modulo] = array();
                        $leitAgrupada[$l->modulo]['pin1'] = 0.0;
                        $leitAgrupada[$l->modulo]['pin2'] = 0.0;
                        $leitAgrupada[$l->modulo]['pin3'] = 0.0;
                        $leitAgrupada[$l->modulo]['pin4'] = 0.0;
                    }

                    $leitAgrupada[$l->modulo]['pin1'] += $l->pin1;
                    $leitAgrupada[$l->modulo]['pin2'] += $l->pin2;
                    $leitAgrupada[$l->modulo]['pin3'] += $l->pin3;
                    $leitAgrupada[$l->modulo]['pin4'] += $l->pin4;
                }

                $ambienteDAO->gravarLeituras($leitAgrupada);

                file_put_contents(TMPPATH . "/leiturasTemp.log", file_get_contents(TMPPATH . "/leituras.log"), FILE_APPEND);
                file_put_contents(TMPPATH . "/leituras.log", '');
            }
            echo 'OK';
        }
    }