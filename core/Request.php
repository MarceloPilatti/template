<?php
namespace Core;
class Request{
    private $get;
    private $post;
    private $files;
    public function setGet($name,$value){
        @$this->get->$name=$value;
    }
    public function setPost($name,$value){
        @$this->post->$name=$value;
    }
    public function setFiles($name,$value){
        @$this->files->$name=$value;
    }
    public function __get($name){
        return $this->$name;
    }
    public function __set($name,$value){
        $this->$name=$value;
    }
}