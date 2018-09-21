<?php
namespace App\Controller;

use App\Enum\Page;
use App\Service\SessionService;
use Core\ApplicationError;
use Core\Controller;
use Core\ErrorType;
use Core\View;

class HomeController extends Controller{
	public function indexAction(){
		try{
    	    $page=Page::HOME;
    	    $pageConfig=null;
    	    SessionService::setPageHeader($pageConfig,$page);
		    
		    if($this->isPost()){
			}else{
			    return new View('home/index',[]);
			}
		}catch(\Throwable $t){
			return ApplicationError::showError($t,ErrorType::ERROR);
		}
	}
}