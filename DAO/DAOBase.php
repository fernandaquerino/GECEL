<?php

class DAOBase{
    
    public function __construct() {
        ;
    }
    
    protected function obterKwatts($amperes){
        $watts = 220.0 * floatval($amperes);
        $kwatts = $watts/1000;
        return $kwatts;
    }
    
    protected function obterPreco($amperes){
        //valor em watts/h(1w) sem bandeira = R$ 0,00016786
        //valor em watts/h(1w)  bandeira verde = R$ 0,00024324
        //valor em Kwatts/h(1w) bandeira verde = R$ 0,24324
        //em 5 minutos, 1 Kwatt equivale a R$ 0,02027
        $watts = 220.0 * floatval($amperes);
        $kwatts = $watts/1000;
        $preco = $kwatts * 0.02027;    
        return $preco;
    }
    
    public function getMonthAbrev($month){        
        $monthAbrev = '';
        switch ($month){
            case '1': 
                $monthAbrev = 'Jan';
                break;
            case '2':
                $monthAbrev = 'Fev';
                break;
            case '3':
                $monthAbrev = 'Mar';
                break;
            case '4':
                $monthAbrev = 'Abr';
                break;
            case '5':
                $monthAbrev = 'Mai';
                break;
            case '6':
                $monthAbrev = 'Jun';
                break;
            case '7':
                $monthAbrev = 'Jul';
                break;
            case '8':
                $monthAbrev = 'Ago';
                break;
            case '9':
                $monthAbrev = 'Set';
                break;
            case '10':
                $monthAbrev = 'Out';
                break;
            case '11':
                $monthAbrev = 'Nov';
                break;
            case '12':
                $monthAbrev = 'Dez';
                break;
        }
        
        return $monthAbrev;
    }
    
    public function getMonthNumber($month){        
        $monthNu = '';
        switch ($month){
            case 'Jan': 
                $monthNu = 1;
                break;
            case 'Fev':
                $monthNu = 2;
                break;
            case 'Mar':
                $monthNu = 3;
                break;
            case 'Abr':
                $monthNu = 4;
                break;
            case 'Mai':
                $monthNu = 5;
                break;
            case 'Jun':
                $monthNu = 6;
                break;
            case 'Jul':
                $monthNu = 7;
                break;
            case 'Ago':
                $monthNu = 8;
                break;
            case 'Set':
                $monthNu = 9;
                break;
            case 'Out':
                $monthNu = 10;
                break;
            case 'Nov':
                $monthNu = 11;
                break;
            case 'Dez':
                $monthNu = 12;
                break;
        }
        
        return $monthNu;
    }
    
}