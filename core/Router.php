<?php
namespace Core;
class Router{
    private $routes;
    private $url;
    public function __construct($routes=[], $url){
        $this->url=$url;
        $this->routes=$routes;
    }
    public function route(){
        $url=$this->url;
        if($url!=='/' && (substr($url,-1)==='/')){
            $url=substr($url,-1);
        }
        $urlArray=explode('/',$url);
        array_shift($urlArray);
        $urlCount=count($urlArray);
        $param=null;
        if($urlCount==1){
            $route=$this->getRoute('/'.$urlArray[0]);
        }else if ($urlCount==2){
            $cName='App\\Controller\\'.ucfirst($urlArray[0]).'Controller';
            if(method_exists($cName,$urlArray[1].'Action')){
                $route=$this->getRoute('/'.$urlArray[0].'/'.$urlArray[1]);
            }else{
                $param=$urlArray[1];
                if(is_numeric($param)){
                    $paramName='{id}';
                }else{
                    $paramName='{slug}';
                }
                $route=$this->getRoute('/'.$urlArray[0].'/'.$paramName);
            }
        }else if ($urlCount==3){
            $param=$urlArray[2];
            $route=$this->getRoute('/'.$urlArray[0].'/'.$urlArray[1].'/{id}');
        }else{
            $this->showNotFoundError();
        }
        $actionName='';
        if($route){
            $routeArray=explode('@', $route);
            $controllerName=$routeArray[0];
            if(count($routeArray)>1){
                $actionName=$routeArray[1];
            }
        }else{
            $controllerName=ucfirst($urlArray[0]).'Controller';
            if($urlCount>1){
                $actionName=$urlArray[1];
            }
        }
        if(!$actionName){
            $actionName='index';
        }
        $controllerClass='App\\Controller\\'.$controllerName;
        if(!class_exists($controllerClass)){
            $this->showNotFoundError();
        }
        $action=$actionName.'Action';
        if(!method_exists($controllerClass,$action)){
            $this->showNotFoundError();
        }
        $controller=new $controllerClass();
        $controller->dispatch($controller, $action, $param);
    }
    private function getRoute($route){
        $routes=$this->routes;
        $exists=array_key_exists($route,$routes);
        return (($exists)?($routes[$route]):(null));
    }
    private function showNotFoundError(){
        $view=ApplicationError::showError(null,ErrorType::NOTFOUND);
        $view->show();
        exit();
    }
    static function redirect($uri,$with=[]){
        if(count($with)>0){
            foreach($with as $key=>$value){
                Session::set($key,$value);
            }
        }
        if(!$uri){
            header("Refresh: 0");
            exit();
        }
        header('Location: '.$uri);
        exit();
    }
}