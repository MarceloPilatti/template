<?php
namespace Core;
use App\DAO\UserDAO;
abstract class Auth{
    public static function userId(){
        return Session::get('userId');
    }
    public static function userName(){
        return Session::get('userName');
    }
    public static function login($userName,$password){
        $userDao=new UserDAO();
        $user=$userDao->getBy('name',$userName);
        if(!$user){
            FlashMessage::setMessage('Usuário e/ou senha inválido(s)',FlashType::ERROR);
            Router::redirect('/login');
        }
        $userPassword=$user->password;
        $result=password_verify($password,$userPassword);
        if(!$result){
            FlashMessage::setMessage('Usuário e/ou senha inválido(s)',FlashType::ERROR);
            Router::redirect('/login');
        }
        if(!session_id())session_start();
        Session::set('logged',true);
        Session::set('userId',$user->id);
        Session::set('userName',$user->name);
        Session::set('minify','');
        if(getenv("APPLICATION_ENV")=="production")Session::set('minify','min');
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