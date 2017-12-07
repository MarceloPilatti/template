<?php
use Core\Application;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

if(!session_id())session_start();
if(!file_exists('../vendor/autoload.php')){
    echo "Vendor folder not found";
    exit();
}
require_once '../vendor/autoload.php';
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');
$path=__DIR__.'app/model/entity';
$isDevMode=false;
if(getenv("APPLICATION_ENV")=='development')$isDevMode=true;
$proxyDir=__DIR__.'/data/DoctrineORMModule/Proxy';
$config = parse_ini_file("../config/config.ini", true );
$dbParams=array (
'driver'=>$config['database']['driver'],
'user'=>$config['database']['user'],
'password'=>$config['database']['password'],
'host'=>$config['database']['host'],
'dbname'=>$config['database']['dbname'],
'charset'=>$config['database']['charset'],
'driverOptions'=>array(
    PDO::MYSQL_ATTR_INIT_COMMAND=> 'SET NAMES utf8'
));
$config=Setup::createAnnotationMetadataConfiguration(array($path),$isDevMode,$proxyDir,null,false);
$entityManager=EntityManager::create($dbParams,$config);
Application::run();