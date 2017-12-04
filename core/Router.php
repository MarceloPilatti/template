<?php
namespace Core;

class Router{
	public function route(){
		$url=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if($url !== '/'){
    		$lastStr=substr($url, -1);
    		if($lastStr === '/'){
    		    $url=substr($url, 0, -1);
    		}
		}
		$urlArray=explode('/', $url);
		array_shift($urlArray);
		if(count($urlArray)==2){
		    $url='/'.$urlArray[0].'/{id}';
		}
		$route=$this->getRoute($url);
		list($controller,$action)=explode('@',$route);
		$class='App\\Controller\\'.$controller;
		if(!class_exists($class)){
		    $view=ApplicationError::showError(null,ErrorType::NOTFOUND);
		    $view->show();
		    exit();
		}
		$c=new $class();
		$a=$action.'Action';
		if(!method_exists($class,$a)){
		    $view=ApplicationError::showError(null,ErrorType::NOTFOUND);
		    $view->show();
		    exit();
		}
        $request=$this->getRequest();
		$view=$c->$a($request);
		$view->show();
	}
	private function getRoute($route){
	    $routes=require_once __DIR__ . "/../app/routes.php";
	    return $routes[$route];
	}
	private function getRequest(){
	    $request = new \stdClass();
	    foreach ($_GET as $key => $value){
	        @$request->get->$key = $value;
	    }
	    foreach ($_POST as $key => $value){
	        @$request->post->$key = $value;
	    }
	    return $request;
	}
	static function redirect($uri, $with = []){
	    if (count($with) > 0){
	        foreach ($with as $key => $value){
	            Session::set($key, $value);
	        }
	    }
		return header('Location: '.$uri);
	}
}