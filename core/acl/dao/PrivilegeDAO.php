<?php
namespace Core\Acl\dao;
use Core\DAO;
class PrivilegeDAO extends DAO{
    public function __construct(){
        $this->entity="Core\\Acl\\Entity\\Privilege";
        parent::__construct();
    }
}