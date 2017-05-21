<?php
class Application {
	protected $controller;
	protected $action;
	private function loadRoute(){
		$this->controller = isset($_REQUEST['c']) ?  $_REQUEST['c'] : 'main';
		$this->action = isset($_REQUEST['a']) ?  $_REQUEST['a'] : 'index';
	}
	public function dispatch(){
		$this->loadRoute();
		$class = ucfirst($this->controller).'Controller';
		if(class_exists($class)) $o_class = new $class;
		else ApplicationError::showError(null, ErrorType::NOTFOUND);
		$method = $this->action.'Action';
		if(method_exists($o_class,$method)) $o_class->$method();
		else ApplicationError::showError(null, ErrorType::NOTFOUND);
	}
	public static function redirect($uri){
		header("Location: $uri");
	}
}