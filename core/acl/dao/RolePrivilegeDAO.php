<?php
namespace Core\Acl\dao;
use Core\DAO;

class RolePrivilegeDAO extends DAO{
	public function __construct() {
		$this->entity = "Core\\Acl\\Entity\\RolePrivilege";
		parent::__construct();
	}
}