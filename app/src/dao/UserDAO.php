<?php
namespace App\DAO;

use Core\DAO;
use Doctrine\ORM\ORMException;

class UserDAO extends DAO{
	public function __construct(){
		$this->entity="App\\Entity\\User";
		parent::__construct();
	}
	public function getByUserName($userName){
		try{
			$repository=$this->em->getRepository($this->entity);
			$criteria=array(
				"name"=>$userName
			);
			$user=$repository->findOneBy($criteria);
			return $user;
		}catch(ORMException $oRME){
			return null;
		}
	}
}