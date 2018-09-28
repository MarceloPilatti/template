<?php
namespace App\Entity;

class FooterLink
{
    private $id;
    private $description;
    private $url;
    private $openNewTab;
    private $footerColumnId;
    public static function rules(){
        return [
            'description' => ['rules'=>'required|max:255'],
            'url' => ['rules'=>'required'],
            'openNewTab' => ['rules'=>'required|int'],
            'footerColumnId' => ['rules'=>'required|foreign-key:one']
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
