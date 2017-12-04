<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Core\Application;

if(!session_id())session_start();
if(!file_exists('../vendor/autoload.php')){
    echo "Vendor folder not found";
    exit();
}
require_once '../vendor/autoload.php';
date_default_timezone_set('America/Sao_Paulo');
$path=__DIR__.'app/model/entity';
$isDevMode=false;
if(getenv("APPLICATION_ENV")=='development')$isDevMode=true;
$proxyDir=__DIR__.'/data/DoctrineORMModule/Proxy';
$config = parse_ini_file("config/config.ini", true );
$dbParams=array (
'driver'=>$config['database']['driver'],
'user'=>$config['database']['user'],
'password'=>$config['database']['password'],
'host'=>$config['database']['host'],
'dbname'=>$config['database']['dbname']);
$config=Setup::createAnnotationMetadataConfiguration(array($path),$isDevMode,$proxyDir,null,false);
$entityManager=EntityManager::create($dbParams,$config);
Application::run();