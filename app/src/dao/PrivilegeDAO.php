<?php
namespace App\dao;
use Core\DAO;
class PrivilegeDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "Core\\Acl\\Entity\\Privilege";
        $this->tableName = "PRIVILEGE";
    }
}