<?php
namespace App\Entity;

class FooterColumn
{
    private $id;
    private $title;
    private $isSubproject;
    public static function rules(){
        return [
            'title' => ['rules'=>'required|min:5|max:30'],
            'isSubproject' => ['rules'=>'int']
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
    public function __set($name, $value)
    {
        $this->$name=$value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
}
