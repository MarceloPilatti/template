<?php
namespace Core;
class Request{
    private $queryData;
    private $postData;
    private $files;
    private $isPost;
    public function __construct(){
        $this->isPost=false;
        if($this->isPost()){
            $this->isPost=true;
        }
        $this->queryData=$this->getQueryData();
        $this->postData=$this->getPostData();
        $this->files=$this->getFiles();
    }
    public function getFormData(){
        $formData=[];
        if($this->isPost){
            $formData=$this->getPostData();
        }else{
            $formData=$this->getQueryData();
        }
        $formData=array_merge($formData,$this->getFiles());
        return $formData;
    }
    private function mapFileParam(&$array, $paramName, $index, $value)
    {
        if (! is_array($value)) {
            $array[$index][$paramName] = $value;
        } else {
            foreach ($value as $i => $v) {
                $this->mapPhpFileParam($array[$index], $paramName, $i, $v);
            }
        }
    }
    public function isPost(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return false;
        }else{
            http_response_code(405);
            die();
        }
    }
    private function getPostData(){
        $formData=[];
        foreach($_POST as $key=>$value){
            $formData[$key]=$value;
        }
        return $formData;
    }
    private function getQueryData(){
        $formData=[];
        foreach($_GET as $key=>$value){
            $formData[$key]=$value;
        }
        return $formData;
    }
    private function getFiles(){
        $formData=[];
        $files = [];
        foreach ($_FILES as $fileName => $fileParams) {
            $files[$fileName] = [];
            foreach ($fileParams as $param => $data) {
                if (! is_array($data)) {
                    $files[$fileName][$param] = $data;
                } else {
                    foreach ($data as $i => $v) {
                        $this->mapFileParam($files[$fileName], $param, $i, $v);
                    }
                }
            }
        }
        $count=0;
        $data=[];
        foreach ($files as $key=>$file){
            $data[$key]=$file;
            $formData[$key]=$data;
            $count++;
            $data=[];
        }
        return $formData;
    }
    public function __get($name){
        return $this->$name;
    }
    public function __set($name,$value){
        $this->$name=$value;
    }
}