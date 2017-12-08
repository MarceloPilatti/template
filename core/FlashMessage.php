<?php
namespace Core;
abstract class FlashType{
    const SUCCESS=1;
    const ERROR=2;
    const WARNING=3;
    const INFO=4;
}
abstract class FlashMessage{
    public static function setMessage($message,$type){
        if(Session::get('message'))Session::unset('message');
        if(Session::get('class'))Session::unset('class');
        Session::set('message',$message.'.');
        Session::set('type',$type);
    }
}