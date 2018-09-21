<?php
namespace App\Entity;
class Social{
    private $id;
    private $name;
    private $class;
    private $url;
    private $showing;
    public static function rules(){
        return [
            'name'=>['rules'=>'required|max:45'],
            'class'=>['rules'=>'required|max:45'],
            'url'=>['rules'=>'required|max:255'],
            'showing'=>['rules'=>'required|default:1']
        ];
    }
    public function getAttrs()
    {
        return get_object_vars($this);
    }
    public function setAttrs($values){
        $attrs=$this->getAttrs();
        foreach ($attrs as $key=>$attr){
            $value=$values[$key];
            if($key!=='id'){
                $value=$values[$key];
                $this->$key=$value;
            }
        }
    }
    public function getAttrs(){
        return get_object_vars($this);
    }
    public function __get($name){
        return $this->$name;
    }
    public function __set($name,$value){
        $this->$name=$value;
    }
}