<?php
namespace Core\Acl\dao;
use Core\DAO;
use Doctrine\ORM\ORMException;

class UserDAO extends DAO {
	public function __construct() {
		$this->entity = "Core\\Acl\\Entity\\User";
		parent::__construct();
	}
	public function getByUserNameAndPassword($userName,$password){
	    try{
	        $repository=$this->em->getRepository($this->entity);
	        $criteria=array(
	            "userName"=>$userName,
	            "password"=>$password
	        );
	        $entity=$repository->findOneBy($criteria);
	        return $entity;
	    }catch(ORMException $oRME){
	        return null;
	    }
	}
}