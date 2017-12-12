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
        if(Session::get('flashMessage'))Session::unset('flashMessage');
        if(Session::get('flashClass'))Session::unset('flashClass');
        $class='';
        if($type==FlashType::SUCCESS){
            $class='alert-success';
        }
        if($type==FlashType::WARNING){
            $class='alert-warning';
        }
        if($type==FlashType::INFO){
            $class='alert-info';
        }
        if($type==FlashType::ERROR){
            $class='alert-danger';
        }
        if(substr($message,-1)!=='!' && substr($message,-1)!=='.'){
            $message=$message.'.';
        }
        Session::set('flashMessage',$message);
        Session::set('flashClass',$class);
    }
}