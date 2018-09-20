<?php
namespace Core;
abstract class Config
{
    public static function getConfig()
    {
        return include __DIR__.'/../config/config.php';
    }
    public static function getDBName()
    {
        $config=self::getConfig();
        $env = getenv('APPLICATION_ENV');
        $dBName=null;
        if ($env=='production') {
            $dBName = $config['db']['dBNameProduction'];
        } else if ($env=='test') {
            $dBName = $config["db"]["dBNameTest"];
        } else {
            $dBName = $config['db']['dBName'];
        }
        return $dBName;
    }
    public static function getVersion()
    {
        $config=self::getConfig();
        $version=$config['version'];
        return $version;
    }
    public static function getUpdatedAt()
    {
        $config=self::getConfig();
        $updatedAt=$config['updatedAt'];
        return $updatedAt;
    }
}