<?php
namespace Core\Acl\dao;
use Core\DAO;

class RoleDAO extends DAO {
	public function __construct() {
		$this->entity = "Core\\Acl\\Entity\\Role";
		parent::__construct();
	}
}