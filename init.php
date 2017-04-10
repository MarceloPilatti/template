<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

@ini_set("display_errors", 1);
@ini_set("log_errors", 1);
@ini_set("error_reporting", E_ALL);

date_default_timezone_set( 'America/Sao_Paulo' );

set_time_limit( 60 );

$path=__DIR__.'/entity';
$proxyDir=__DIR__.'/data/DoctrineORMModule/Proxy';
$isDevMode = true;
$dbParams=array (
		'driver'=>'pdo_mysql',
		'user'=>'root',
		'password'=>'',
		'host'=>'127.0.0.1',
		'dbname'=>'');
$config=Setup::createAnnotationMetadataConfiguration(array($path),$isDevMode,$proxyDir,null,false);
$entityManager=EntityManager::create($dbParams,$config);
