<?php
class UserDAO extends DAO {
	public function __construct() {
		$this->entity = "entity\\User";
		parent::__construct();
	}
}