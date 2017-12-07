<?php
namespace App\DAO;
use Core\DAO;
class HomeDAO extends DAO{
	public function __construct(){
		$this->entity="App\\Entity\\Home";
		parent::__construct();
	}
}