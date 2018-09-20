<?php
namespace App\dao;
use Core\DAO;
class RoleDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "Core\\Acl\\Entity\\Role";
        $this->tableName = "ROLE";
    }
}