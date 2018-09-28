<?php
namespace App\Entity;
class PageConfig{
    private $id;
    private $title;
    private $description;
    private $url;
    private $robots;
    private $page;
    public static function rules(){
        return [
            'title'=>['rules'=>'required'],
            'description'=>['rules'=>'required'],
            'url'=>['rules'=>'required'],
            'robots'=>['rules'=>'required'],
            'page'=>['rules'=>'required|int']
        ];
    }
    public function getAttrs()
    {
        return get_object_vars($this);
    }
    public function setAttrs($values){
        $attrs=$this->getAttrs();
        $attrs=array_keys($attrs);
        foreach ($attrs as $key){
            if($key!=='id'){
                $value=$values[$key];
                $this->$key=$value;
            }
        }
    }
    public function __get($name){
        return $this->$name;
    }
    public function __set($name,$value){
        $this->$name=$value;
    }
}