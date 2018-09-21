<?php
namespace Core;
abstract class Session{
    public static function set($key,$value){
        $_SESSION[$key]=$value;
    }
    public static function get($key){
        return $_SESSION[$key];
    }
    public static function setErrors($data){
        $errors=self::get('errors');
        if(is_array($errors)){
            $errors=array_merge($errors,$data);
            self::set('errors', $errors);
            return;
        }
        self::set('errors', $data);
    }
    public static function setInputs($data){
        $inputs=self::get('inputs');
        if(is_array($inputs)>0){
            $inputs=array_merge($inputs,$data);
            self::set('inputs', $inputs);
            return;
        }
        self::set('inputs', $data);
    }
    public static function unset($keys){
        if(!is_array($keys)){
            $keys=array($keys);
        }
        foreach($keys as $key){
            unset($_SESSION[$key]);
        }
    }
    public static function destroy(){
        if(!session_id())session_start();
        session_unset();
        session_destroy();
    }
    
    public static function init(){
        
        $version=Config::getVersion();
        $updatedAt=Config::getUpdatedAt();
        $dBName=Config::getDBName();
        $env = getenv('APPLICATION_ENV');
        self::set('minify','');
        if ($env=='production') {
            self::set('minify','.min');
        }
        self::set('version',$version);
        self::set('updatedAt',$updatedAt);
        self::set('dBName',$dBName);
    }
}