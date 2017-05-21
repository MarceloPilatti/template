<?php
class RolePrivilegeDAO extends DAO {
	public function __construct() {
		$this->entity = "entity\\RolePrivilege";
		parent::__construct();
	}
}