<?php
namespace Dao;
use Dao\DAO;
class UserDAO extends DAO {
	public function __construct() {
		$this->entity = "Entity\\User";
		parent::__construct();
	}
}