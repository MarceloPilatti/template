<?php
class MainController{
	public function indexAction(){
		try {
			$view = new View('../app/view/main/index.phtml');
			$view->showContent();
		} catch (Throwable $t) {
			ApplicationError::showError($t, ErrorType::ERROR);
		}
	}
}