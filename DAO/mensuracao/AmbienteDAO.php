<?php
require_once(DAOPATH . '/DAOBase.php');

class AmbienteDAO extends DAOBase{
    public function __construct() {
      parent::__construct();
    }
    
    public function gravarLeituras($args){ 

        $IPs = '';        
        foreach($args as $ip=>$dados){
            $IPs .= "'$ip',";
        }
        $IPs = substr($IPs, 0,-1);
        
        $mysqli = new mysqli(DB_ADRESS, DB_USER, DB_PASS, DB_NAME);
        
        if (mysqli_connect_errno()) {
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }

        $sql = "SELECT 
            PA_IDPONTOAPRESENTACAO,
            PO_CDPONTO,
            MO_CDIP
        FROM
            MO_MODULO MO INNER JOIN PO_PONTO PO ON 
                MO.MO_IDMODULO = PO.MO_IDMODULO
            INNER JOIN PA_PONTOAPRESENTACAO PA ON
                PO.PO_IDPONTO = PA.PO_IDPONTO
            WHERE MO_CDIP IN($IPs)
                AND PA_DTDESATIVADO IS NULL";
                
        $result = $mysqli->query($sql);
        $idPins = array();
        
        while($row = $result->fetch_assoc()){
            array_push($idPins, $row);
        }   
        
        foreach($args as $ip=>$dados){
                       
                $aux = $idPins;
                foreach($aux as $id){
                    if($id['MO_CDIP'] == $ip){  
                           
                        $stmt = $mysqli->prepare("INSERT INTO LE_LEITURA VALUES (NULL,?,?,?,?)");
                        $stmt->bind_param('sdii',
                                           $data,
                                           $pin,
                                           $bandeira,
                                           $idPin);
                        
                        $data = date("Y/m/d H:i:s");
                        $pin = $dados[$id['PO_CDPONTO']];
                        $bandeira = 1;
                        $idPin = $id['PA_IDPONTOAPRESENTACAO'];
                        
                        $stmt->execute();

                    }
                }
        }
        
        $stmt->close();
        $mysqli->close();
    }
    
    public function obterConsumoAno(){
        $sql = 'SELECT ano, SUM(consumo) consumo  FROM
                (SELECT YEAR(LE_DTLEITURA) ano, LE_QTCONSUMO consumo 
                FROM GECEL_DB.LE_LEITURA LE
                INNER JOIN GECEL_DB.BT_BANDEIRATARIF BT
                 ON LE.BT_IDBANDEIRATARIF = BT.BT_IDBANDEIRATARIF
                ) AS X
                GROUP BY ANO';
        
        $mysqli = new mysqli(DB_ADRESS, DB_USER, DB_PASS, DB_NAME);
        
        if (mysqli_connect_errno()) {
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }
        
        $result = $mysqli->query($sql);
        $resultado = array();
        
        while($row = $result->fetch_assoc()){
            array_push($resultado, $row);
        }                
        
        $retorno = array();
        foreach($resultado as $rs){
            $obj = (object)$rs;
            $obj->preco   = $this->obterPreco($obj->consumo);
            $obj->consumo = $this->obterKwatts($obj->consumo);
            array_push($retorno, $obj);
        }        
        return $retorno;
    }
    
    public function obterConsumoMes($args){     
        $mysqli = new mysqli(DB_ADRESS, DB_USER, DB_PASS, DB_NAME);
        
        if (mysqli_connect_errno()) {
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }
                
        $retorno = array();
        
        $sql = "SELECT mes, SUM(consumo) consumo FROM
                (   SELECT MONTH(LE_DTLEITURA) mes, LE_QTCONSUMO consumo 
                    FROM LE_LEITURA LE
                 	INNER JOIN BT_BANDEIRATARIF BT
                 	ON LE.BT_IDBANDEIRATARIF = BT.BT_IDBANDEIRATARIF
                    WHERE YEAR(LE_DTLEITURA) = {$args['ano']}
                )   AS X                
                GROUP BY mes";
        
        $result = $mysqli->query($sql);
                
        while($row = $result->fetch_assoc()){
            $aux = [
                    'mes' => $this->getMonthAbrev($row['mes']),
                    'preco' => $this->obterPreco($row['consumo']),
                    'consumo' => $this->obterKwatts($row['consumo'])
                   ];
            array_push($retorno,(object)$aux);            
        }
        
        return $retorno;        
    }
    
    public function obterConsumoMesDia($args){
        $mes = $this->getMonthNumber($args['mes']);
        
        $sql = "SELECT dia, SUM(consumo) consumo FROM
                (
                    SELECT day(LE_DTLEITURA) dia, LE_QTCONSUMO consumo
                    FROM LE_LEITURA LE
                    INNER JOIN BT_BANDEIRATARIF BT
                    ON LE.BT_IDBANDEIRATARIF = BT.BT_IDBANDEIRATARIF
                    WHERE MONTH(LE_DTLEITURA) = {$mes}
                ) AS X
                GROUP BY dia";
        
        $mysqli = new mysqli(DB_ADRESS, DB_USER, DB_PASS, DB_NAME);
        
        if (mysqli_connect_errno()) {
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }
                
        $retorno = array();
        $result = $mysqli->query($sql);
        
                
        while($row = $result->fetch_assoc()){             
            $aux = [$row['dia'], $this->obterKwatts($row['consumo']), $this->obterPreco($row['consumo'])];
            array_push($retorno,$aux);            
        }
        
        return $retorno; 
    }
    
    public function obterConsumoDia($args){
        $mes = $this->getMonthNumber($args['mes']);
                
        $sql = "SELECT 
                DATE_FORMAT(LE.LE_DTLEITURA, '%H:%i:%s') Hora,
                DATE_FORMAT(LE.LE_DTLEITURA, '%d/%m/%Y') Dia,
                PA.PA_DCPONTOAPRESENTACAO,
                LE.LE_QTCONSUMO

                FROM LE_LEITURA LE
                INNER JOIN PA_PONTOAPRESENTACAO PA
                ON LE.PA_IDPONTOAPRESENTACAO = PA.PA_IDPONTOAPRESENTACAO
                INNER JOIN BT_BANDEIRATARIF BT
                ON LE.BT_IDBANDEIRATARIF = BT.BT_IDBANDEIRATARIF

                where
                date(le_dtleitura) = date('{$args['ano']}-{$mes}-{$args['dia']}')";
                

        $mysqli = new mysqli(DB_ADRESS, DB_USER, DB_PASS, DB_NAME);
        
        if (mysqli_connect_errno()) {
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }
                
        $retorno = array();
        $result = $mysqli->query($sql);
        
                
        while($row = $result->fetch_object()){            
            $row->PA_DCPONTOAPRESENTACAO = utf8_decode($row->PA_DCPONTOAPRESENTACAO);
            $row->LE_QTCONSUMO = $this->obterKwatts($row->LE_QTCONSUMO);
            $row->preco = $this->obterPreco($row->LE_QTCONSUMO);
            array_push($retorno,$row);
        }        
        
        return $retorno; 
    }
}