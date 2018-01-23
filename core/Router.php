<?php
namespace Core;
class Router{
    private $routes;
    private $url;
    public function __construct(array $routes, $url){
        $this->url=$url;
        $this->routes=$routes;
    }
    public function route(){
        $entity=null;
        $url=$this->url;
        if($url!=='/'){
            $url=((substr($url,-1)==='/')?(substr($url,0,-1)):($url)); // Remove the last '/' in case exists
        }
        $urlArray=explode('/',$url);
        array_shift($urlArray); // Remove the first element of the url exploded (It's an ampty element)

        if(count($urlArray)==3){ // Case site is like site.com/user/get/1
            if(is_numeric($urlArray[2])){
                $route=$this->getRoute('/'.$urlArray[0].'/'.$urlArray[1].'/{id}');
            }else{
                $route=$this->getRoute('/'.$urlArray[0].'/'.$urlArray[1].'/{slug}');
            }
        }elseif(count($urlArray)==2){// Case site is like site.com/user/1 or site.com/user/form
            if(is_numeric($urlArray[1])){
                $route=$this->getRoute('/'.$urlArray[0].'/{id}');
            }else{
                $route=$this->getRoute('/'.$urlArray[0].'/'.$urlArray[1]);
                if(!$route){
                    $route=$this->getRoute('/'.$urlArray[0].'/{slug}');
                }
            }
        }elseif(count($urlArray)==1){
            $route=$this->getRoute('/'.$urlArray[0]);
        }else{
            $this->showNotFoundError();
        }

        if(!$route){
            $this->showNotFoundError();
        }
        list($controller,$action)=explode('@',((is_array($route)?($route[0]):($route))));

        if(is_array($route)){
            if(count($route)!=1){
                $entityName=$route[1];
                $paramType=$route[2];
                $entityDAOClass="App\\DAO\\".ucfirst($entityName).'DAO';
                $entityDAO=new $entityDAOClass();
                if($paramType=='slug'){
                    $entitySlug=end($urlArray);
                    $entity=$entityDAO->getBySlug($entitySlug);
                    if(!$entity){
                        $this->showNotFoundError();
                    }
                }else if($paramType=='id'){
                    $entityId=(int)end($urlArray);
                    $entity=$entityDAO->getById($entityId);
                    if(!$entity){
                        $this->showNotFoundError();
                    }
                }
            }
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
        $result=$c->$a($request,$entity);
        if(is_string($result)){
            echo $result;
            exit();
        }
        $result->show();
    }
    private function getRoute($route){
        $routes=$this->routes;
        $exists=array_key_exists($route,$routes);
        return (($exists)?($routes[$route]):(null));
    }
    private function getRequest(){
        $request=new \stdClass();
        foreach($_GET as $key=>$value){
            @$request->get->$key=$value;
        }
        foreach($_POST as $key=>$value){
            @$request->post->$key=$value;
        }
        foreach($_FILES as $key=>$value){
            @$request->files->$key=$value;
        }
        return $request;
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
        header('Location: '.$uri);
        exit();
    }
}