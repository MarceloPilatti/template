<?php
class PrivilegeDAO extends DAO {
	public function __construct() {
		$this->entity = "entity\\Privilege";
		parent::__construct();
	}
}