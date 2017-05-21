<?php
class RoleDAO extends DAO {
	public function __construct() {
		$this->entity = "entity\\Role";
		parent::__construct();
	}
}