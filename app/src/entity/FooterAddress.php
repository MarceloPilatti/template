<?php
namespace App\Entity;

class FooterAddress
{
    private $id;
    private $street;
    private $neighborhood;
    private $number;
    private $complement;
    private $city;
    private $state;
    private $phone;
    private $email;
    public static function rules(){
        return [
            'street' => ['rules'=>'required|max:255'],
            'neighborhood' => ['rules'=>'required|max:255'],
            'number' => ['rules'=>'required|max:5'],
            'complement' => ['rules'=>'max:30'],
            'city' => ['rules'=>'required|max:255'],
            'state' => ['rules'=>'required|max:2'],
            'phone' => ['rules'=>'required|max:15'],
            'email' => ['rules'=>'required']
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
