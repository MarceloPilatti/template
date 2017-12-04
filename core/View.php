<?php
namespace Core;
class View{
	private $viewPath;
	private $layoutPath;
	private $errors;
	private $inputs;
	function __construct($viewPath=null,$params=null,$layoutPath=null){
	    if($layoutPath==null)$layoutPath=__DIR__.'/../app/view/layout.phtml';
		$viewPath=__DIR__.'/../app/view/'.$viewPath.'.phtml';
		if(!isset($layoutPath)||!file_exists($layoutPath))throw new \Exception('View file '.$layoutPath.' not found');
		if(!isset($viewPath)||!file_exists($viewPath))throw new \Exception('View file '.$viewPath.' not found');
		$this->layoutPath=$layoutPath;
		$this->viewPath=$viewPath;
		if(isset($params)){
			foreach ($params as $key=>$value){
				$this->$key=$value;
			}
		}
		if (Session::get('errors')) {
		    $this->errors = Session::get('errors');
		    Session::unset('errors');
		}
		if (Session::get('inputs')) {
		    $this->inputs = Session::get('inputs');
		    Session::unset('inputs');
		}
	}
	public function show(){
		return require_once $this->layoutPath;
	}
	public function content(){
	    return require_once $this->viewPath;
	}
}