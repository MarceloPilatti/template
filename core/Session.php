<?php
namespace Core;
abstract class Session{
    public static function set($key,$value){
        $_SESSION[$key]=$value;
    }
    public static function get($key){
        if(isset($_SESSION[$key]))return $_SESSION[$key];
        return false;
    }
    public static function setErrors($data){
        $errors=Session::get('errors');
        if(is_array($errors)){
            $errors=array_merge($errors,$data);
            Session::set('errors', $errors);
            return;
        }
        Session::set('errors', $data);
    }
    public static function setInputs($data){
        $inputs=Session::get('inputs');
        if(is_array($inputs)>0){
            $inputs=array_merge($inputs,$data);
            Session::set('inputs', $inputs);
            return;
        }
        Session::set('inputs', $data);
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
}