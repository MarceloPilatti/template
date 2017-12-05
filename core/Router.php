<?php
namespace Core;

class Router{
    public function route(){
        $entity=null;
        $url=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Get URL path like /site/create
        if($url!=='/'){
            $url=((substr($url, -1)==='/')?(substr($url, 0, -1)):($url)); // Remove the last '/' in case exists
        }
        $urlArray=explode('/', $url);
        array_shift($urlArray); // Remove the first element of the url exploded (It's an ampty element)
        if(count($urlArray)==1){ // If the url has just the /site
            $route=$this->getRoute('/'.$urlArray[0]);
            if(!$route){
                $this->showNotFoundError();
            }
            list($controller,$action)=explode('@',$route);
        }else if(count($urlArray)==2){ // If the url has an id like /site/1
            $route=$this->getRoute('/'.$urlArray[0].'/{id}');
            if(!$route){
                $this->showNotFoundError();
            }
            // Get the entity based on the route
            $entityName=substr($route[1], 0, strpos($route[1], 'Id'));
            $entityDAOClass="App\\DAO\\".ucfirst($entityName).'DAO';
            $entityDAO=new $entityDAOClass();
            $entityId=(int)$urlArray[1];
            $entity=$entityDAO->getById($entityId);
            if(!$entity){
                $this->showNotFoundError();
            }
            list($controller,$action)=explode('@',$route[0]);
        }else{
            $this->showNotFoundError();
        }
        $class='App\\Controller\\'.$controller;
        if(!class_exists($class)){
            $this->showNotFoundError();
        }
        $c=new $class();
        $a=$action.'Action';
        if(!method_exists($class,$a)){
            $this->showNotFoundError();
        }
        $request=$this->getRequest();
        $view=$c->$a($request,$entity);
        $view->show();
    }
    private function getRoute($route){
        $routes=require_once __DIR__ . "/../app/routes.php";
        $exists=array_key_exists($route, $routes);
        return (($exists)?($routes[$route]):(null));
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
    private function showNotFoundError(){
        $view=ApplicationError::showError(null,ErrorType::NOTFOUND);
        $view->show();
        exit();
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