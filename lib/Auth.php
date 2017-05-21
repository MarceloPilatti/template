<?php
class Auth{
	public static function login($userName, $password, $captcha=null){
		if(!session_id()) session_start();
		if($captcha){		
			if(!isset($_SESSION['attempts'])) $_SESSION['attempts'] = 0;
			if($_SESSION['attempts'] == 3){
				if($captch && $captcha != $_SESSION['captcha']){
					FlashMessage::setMessage('Os caracteres digitados não correspondem com os da imagem', FlashType::ERROR, "/");
				}
			}
		}
		$userDao = new UserDAO();
		$user = $userDao->checkLogin($userName, $password);
		if($user){
			$_SESSION['logged'] = true;
			$_SESSION['userId'] = $user->id;
			$_SESSION['userName'] = $user->nameName;
			if($captcha) $_SESSION['attempts'] = 0;
		}
		else{
			if($captcha) $_SESSION['attempts'] += 1;
			FlashMessage::setMessage("Usuário e/ou senha inválido(s)", FlashType::ERROR, "/");
		}
	}
	public static function logout(){
		if(!session_id()) session_start();
		$_SESSION['logged'] = false;
		session_unset();
		session_destroy();
		Application::redirect('/');
	}
	public static function isLogged(){
		if(!empty($_SESSION['logged'])) return true;
		else return false;
	}
}