<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

if (file_exists('vendor/autoload.php')) require_once 'vendor/autoload.php';
else echo "Vendor folder not found";
spl_autoload_register(function($class) {
	if(file_exists('app/controller/'.ucfirst($class).'.php')){
		require_once 'app/controller/'.ucfirst($class).'.php';
	}
	if(file_exists('app/model/dao/'.ucfirst($class).'.php')){
		require_once 'app/model/dao/'.ucfirst($class).'.php';
	}
	if(file_exists('lib/'.ucfirst($class).'.php')){
		require_once 'lib/'.ucfirst($class).'.php';
	}
});
	
	@ini_set("display_errors", 1);
	@ini_set("log_errors", 1);
	@ini_set("error_reporting", E_ALL);
	date_default_timezone_set( 'America/Sao_Paulo' );
	set_time_limit(60);
	
	$path=__DIR__.'app/model/entity';
	$proxyDir=__DIR__.'/data/DoctrineORMModule/Proxy';
	if(getenv("APPLICATION_ENV") == 'development')$isDevMode=true;
	else $isDevMode=false;
	$dbParams=array (
			'driver'=>'pdo_mysql',
			'user'=>'root',
			'password'=>'',
			'host'=>'127.0.0.1',
			'dbname'=>'');
	$config=Setup::createAnnotationMetadataConfiguration(array($path),$isDevMode,$proxyDir,null,false);
	$entityManager=EntityManager::create($dbParams,$config);