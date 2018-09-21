<?php
$maintenancePath='maintenance.ini';
$maintenanceConfig=parse_ini_file($maintenancePath);
$maintenance=$maintenanceConfig['maintenance'];
$backTime=$maintenanceConfig['backTime'];

$path='version.ini';
$versionConfig=parse_ini_file($path, false);
$version=$versionConfig['version'];
$updatedAt=$versionConfig['updatedAt'];

$server='localhost';
$user='root';
$password='250691mp';
return [
    'send_account'=>[
        'host'=>'outlook.office365.com',
        'mail_user'=>'marcelo.silva@funcate.org.br',
        'mail_password'=>'250691Mp',
//         'mail_password'=>'J/HqQ4G3',
        'port'=>587,
        'ssl'=>'tls'
    ],
    'webmaster_account'=>[
        'mail_user'=>'marcelo.silva@funcate.org.br'
    ],
    'db'=>[
        'dBName'=>'TEMPLATE',
        'dBNameProduction'=>'TEMPLATE',
        'dBNameTest'=>'TEMPLATE',
        'server'=>$server,
        'user'=>$user,
        'password'=>$password,
    ],
    'version'=>$version,
    'updatedAt'=>$updatedAt,
    'maintenance'=>$maintenance,
    'backTime'=>$backTime
];