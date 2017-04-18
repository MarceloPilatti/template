<?php
namespace App\dao;
require_once 'DAO.php';
class ExampleDAO extends DAO {
	public function __construct() {
		$this->entity = "App\\entity\\Example";
		parent::__construct();
	}
}