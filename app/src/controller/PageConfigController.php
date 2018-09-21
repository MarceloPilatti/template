<?php
namespace App\Controller;

use App\Entity\PageConfig;
use App\dao\PageConfigDAO;
use Core\ApplicationError;
use Core\Auth;
use Core\Controller;
use Core\ErrorType;
use Core\Session;
use Core\Validator;
use Core\FlashType;

class PageConfigController extends Controller{
	public function formAction(){
		try{
		    $isLogged=Auth::isLogged();
		    if(!$isLogged){
		        $this->redirectTo('/');
		    }
		    $formData=$this->getFormData();
		    $pageConfigId=$this->getFormData('pageConfigId');
		    $pageConfigUrl=$this->getFormData('pageConfigUrl');
		    $dBName=Session::get('dBName');
		    $pageConfigDAO=new PageConfigDAO();
		    $pageConfigDAO->begin();
	        $validator=new Validator($formData, [PageConfig::rules()], [PageConfig::class], $pageConfigId, $dBName);
	        $isValidated=$validator->validateForm();
	        if($isValidated===1){
	            return $this->showMessage('Não possível salvar as alterações', FlashType::ERROR, $pageConfigUrl);
	        }
	        if($isValidated===2){
	            $pageConfigDAO->rollback();
	            return $this->showMessage('Não possível salvar as alterações', FlashType::ERROR, $pageConfigUrl);
	        }
	        $pageConfigDAO->commit();
	        return $this->showMessage('Alterações realizadas com sucesso!', FlashType::SUCCESS, $pageConfigUrl);
		}catch(\Throwable $t){
			return ApplicationError::showError($t,ErrorType::ERROR);
		}
	}
}