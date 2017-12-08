<?php
namespace Core;
class Json{
    private $params;
    function __construct($params=null){
        $this->params=$params;
    }
    public function show(){
        echo json_encode($this->params);
        exit();
    }
}