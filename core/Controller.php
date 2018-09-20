<?php
namespace Core;
abstract class Controller{
    private $request;
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
}