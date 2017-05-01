<?php

class MainController{
	public function indexAction(){
		try {
	// 		$view = new View('app/view/main/index.phtml');
	// 		$view->params = array();
	// 		$view->showContent();
	// 		Application::redirect('?c=main&a=index');
		} catch (Throwable $t) {
			ApplicationError::showError($t, ErrorType::ERROR);
		}
	}
}