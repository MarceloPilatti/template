<?php
namespace App\Entity;
class Role{
    private $id;
    private $name;
    private $isAdmin;
    public static function rules(){
        return [
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