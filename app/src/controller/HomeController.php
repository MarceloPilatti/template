<?php
namespace App\Controller;

use Core\Application;
use Core\ApplicationError;
use Core\Auth;
use Core\ErrorType;
use Core\FlashMessage;
use Core\FlashType;
use Core\Session;
use Core\View;

class HomeController{
	public function indexAction($request){
		try{
			Session::set('selectedItem', 1);
			return new View('home/index',array());
		}catch(\Throwable $t){
			return ApplicationError::showError($t,ErrorType::ERROR);
		}
	}
	public function formAction($request){
	    if(!Auth::isLogged()){
	        FlashMessage::setMessage('Você precisa estar logado para acessar essa página', FlashType::ERROR);
	        Application::redirect('/');
	    }
// 	    $home = new Home();

// 	    $data = [
// 	        'title' => $request->post->title,
// 	        'content' => $request->post->content
// 	    ];

// 	    if (Validator::make($data, $home->rules())) {
//             Application::redirect('/home/form');
// 	    }
	}
}