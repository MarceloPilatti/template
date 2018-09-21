<?php
namespace App\Controller;

use App\Service\SessionService;
use Core\ApplicationError;
use Core\Auth;
use Core\Controller;
use Core\ErrorType;
use Core\FlashMessage;
use Core\FlashType;
use Core\Router;
use Core\Session;
use Core\View;

class LoginController extends Controller{
	public function indexAction(){
		try{
    	    $pageConfig=null;
    	    $page=null;
    	    SessionService::setPageHeader($pageConfig,$page);
		    
			$attempts=Session::get('attempts');
		    if($this->isPost()){
    			$env = getenv('APPLICATION_ENV');
    			if ($env=='production') {
    			    if($attempts>=3){
    			        $recaptchResponse=$this->getFormData('g-recaptcha-response');
    			        if(isset($recaptchResponse)) {
    			            $secretKey = '';
    			            $remoteIp = $_SERVER['REMOTE_ADDR'];
    			            $reCaptchaValidation = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$recaptchResponse."&remoteip=".$remoteIp);
    			            $result = json_decode($reCaptchaValidation, true);
    			            if($result['success'] != 1) {
    			                return $this->showMessage('Não foi possível realizar o login.', 'main-error', '/login');
    			            }
    			        }
    			    }
    			}
    		    $userName=$this->getFormData('userName');
    		    $password=$this->getFormData('password');
				if(!isset($userName)){
					FlashMessage::setMessage("Digite o usuário",FlashType::ERROR, '/login');
				}
				if(!isset($password)){
				    FlashMessage::setMessage("Digite a senha",FlashType::ERROR, '/login');
				}
				Auth::login($userName,$password);
				Router::redirect('/');
			}else{
			    return new View('login/index',['attempts'=>$attempts]);
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