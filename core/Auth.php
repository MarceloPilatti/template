<?php
namespace Core;

use App\dao\UserDAO;

abstract class Auth{
    public static function user(){
        return Session::get('userName');
    }
    public static function login($userName,$password){
        $userDao=new UserDAO();
        $user=$userDao->getBy(['userName'=>$userName]);
        $attempts=Session::get('attempts');
        if(!$attempts){
            $attempts=0;
        }
        if(!$user){
            Session::set('attempts',++$attempts);
            FlashMessage::setMessage('Usuário inválido',FlashType::ERROR, '/login');
        }
        $userPassword=$user->password;
        $result=password_verify($password,$userPassword);
        if(!$result){
            Session::set('attempts',++$attempts);
            FlashMessage::setMessage('Senha inválida',FlashType::ERROR, '/login');
        }
        if(!session_id())session_start();
        Session::set('logged',true);
        Session::set('user',$user);
        Session::set('attempts',0);
        if(getenv("APPLICATION_ENV")!="development")Session::set('minify','.min');
        Router::redirect('/');
    }
    public static function logout(){
        Session::destroy();
        Router::redirect('/');
    }
    public static function isLogged(){
        return (Session::get('logged')==true);
    }
}