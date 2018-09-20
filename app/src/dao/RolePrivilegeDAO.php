<?php
namespace App\dao;
use Core\DAO;
class RolePrivilegeDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "Core\\Acl\\Entity\\RolePrivilege";
        $this->tableName = "ROLE_PRIVILEGE";
    }
}