<?php
namespace App\Entity;
class User{
    private $id;
    private $userName;
    private $password;
    public static function rules(){
        return [
            'userName'=>'required',
            'password'=>'required|password|max:10'
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