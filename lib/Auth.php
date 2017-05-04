<?php
class Auth{
	public static function login($userName, $password){
		$userDao = new UserDAO();
		$user = $userDao->checkLogin($userName, $password);
		if($user){
			if(!session_id()) session_start();
			$_SESSION['logged'] = true;
			$_SESSION['userId'] = $user->id;
			$_SESSION['userName'] = $user->nameName;
		}
		else{
			FlashMessage::setMessage("Usuário e/ou senha inválido(s)", FlashType::ERROR);
		}
		Application::redirect('/');
	}
	public static function logout(){
		if(!session_id()) session_start();
		$_SESSION['logged'] = false;
		session_unset();
		session_destroy();
		Application::redirect('/');
	}
	public static function isLogged(){
		if(!empty($_SESSION['logged'])){
			return true;
		}
		else{
			return false;
		}
	}
}