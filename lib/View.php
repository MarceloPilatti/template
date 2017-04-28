<?php
class View{
	private $content;
	private $view;
	private $params;
	function __construct($view=null, $params=null) {
		if($view != null) $this->setView($view);
		$this->params = $params;
	}	
	public function setView($view){
		if(file_exists($view)) $this->view = $view;
		else throw new Exception("View File '$view' not found");		
	}
	public function getContent(){
		ob_start();
		if(isset($this->view)) require_once $this->view;
		$this->content = ob_get_contents();
		ob_end_clean();
		return $this->content;	
	}
	public function showContent(){
		echo $this->getContent();
		exit;
	}
	public function __get($name) {
		return $this->$name;
	}
	public function __set($name, $value) {
		$this->$name = $value;
	}
}