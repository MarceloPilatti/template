<?php
namespace Core;
abstract class Controller{
    private $request;
    
    public function dispatch($controller, $action, $param) {
        Session::init();
        $result=$controller->$action($param);
        if(is_string($result)){
            echo $result;
            exit();
        }
        return $result->show();
    }
    
    public function getRequest() {
        if(!$this->request){
            $this->request=new Request();
        }
        return $this->request;
    }
    public function getFormData($key=null)
    {
        $request=$this->getRequest();
        $formData=$request->getFormData();
        if($key){
            return $formData[$key];
        }
        return $formData;
    }
    public function isPost()
    {
        $request=$this->getRequest();
        return $request->isPost();
    }
    public function redirectTo($uri=null)
    {
        Router::redirect($uri);
    }
    public function showMessage($message, $type, $redirectTo=false)
    {
        FlashMessage::setMessage($message, $type, $redirectTo);
    }
}