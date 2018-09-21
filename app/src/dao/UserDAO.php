<?php
namespace App\dao;
use Core\DAO;
class UserDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "App\\Entity\\User";
        $this->tableName = "USER";
    }
}