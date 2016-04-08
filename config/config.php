<?php
### FOR DEBUG ########
ini_set("log_errors", 1);
// ini_set("error_log", "/tmp/php-error.log");
######################
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Accept, SOAPAction, Origin, Authorization");
header('Content-type: application/json');

use Phalcon\Config\Adapter\Ini as ConfigIni;

$config = new ConfigIni("config/config.ini");
// factory
$di = new \Phalcon\DI\FactoryDefault();

//Set up the database service
$di->set('db', function() use ($config){
  return new \Phalcon\Db\Adapter\Pdo\Postgresql(array(
    "host"      => $config->database->host,
    "username"  => $config->database->username,
    "password"  => $config->database->password,
    "dbname"    => $config->database->dbname,
    "schema"    => $config->database->schema
  ));
});

$di->set('jwt', function(){
  return (object) array(
    'secret' => md5('m1S3Cr3T3'),
    'type' => array('HS256')
  );
});

$di->set('routerignore', function(){
  return array(
    '/api/auth',
    '/api/version',
    '/api/test'
  );
});
