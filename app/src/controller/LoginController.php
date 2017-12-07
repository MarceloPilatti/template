<?php
namespace App\Controller;

use Core\ApplicationError;
use Core\Auth;
use Core\ErrorType;
use Core\FlashMessage;
use Core\FlashType;
use Core\Router;
use Core\View;

class LoginController{
	public function indexAction($request){
		try{
			if(!empty($_POST)){
			    $userName=$request->post->userName;
			    $password=$request->post->password;
				if(!isset($userName)){
					FlashMessage::setMessage("Usuário e/ou senha inválido(s)",FlashType::ERROR);
				}
				if(!isset($password)){
					FlashMessage::setMessage("Usuário e/ou senha inválido(s)",FlashType::ERROR);
				}
				Auth::login($userName,$password);
				Router::redirect('/');
			}else{
				return new View('login/index');
			}
		}catch(\Throwable $t){
			return ApplicationError::showError($t,ErrorType::ERROR);
		}
	}
	public function logoutAction(){
		try{
			Auth::logout();
		}catch(\Throwable $t){
			return ApplicationError::showError($t,ErrorType::ERROR);
		}
	}
}