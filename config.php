<?php

define('SESSION_NAME', 'GECEL');
define('IS_DEBUG_ENABLED', true);
//define('IS_DEBUG_ENABLED', false);

define('CHARSET', 'UTF-8');
define('DB_CHARSET', 'UTF8');
define('SERVERPATH', '/GECEL/');
 
define('DB_ADRESS', 'localhost');
define('DB_NAME', 'GECEL_DB');
define('DB_USER', 'root');
define('DB_PASS', 'root');

define('SYSPATH', dirname(__file__));
define('DAOPATH', dirname(__file__) . '/DAO');
define('CTRLPATH', dirname(__file__) . '/controller');
define('VIEWPATH', dirname(__file__) . '/view');
define('TPLPATH', dirname(__file__) . '/template');
define('JSPATH', dirname(__file__) . '/js');
define('TMPPATH', dirname(__file__) . '/tmp');
define('LIBPATH', dirname(__file__) . '/lib');
define('LOGPATH', dirname(__file__) . '/log');
define('CSSPATH', dirname(__file__) . '/css');
define('INIT_CTRL', 'usuario/Login');

// Email
define('DEFAULT_NAME', 'Não Responder');
define('DEFAULT_EMAIL', 'naoresponder@GECEL.hue.br');
