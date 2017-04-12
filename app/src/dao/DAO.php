<?php
namespace App\dao;
class DAO {
	protected $entity;
	protected $em;
	public function __construct() {
		global $entityManager;
		$this->em = $entityManager;
	}
	public function addOrUpdate($data) {
		try {
			$this->em->persist ( $data );
			$this->em->flush ();
			return true;
		} catch ( \Exception $e ) {
			return false;
		}
	}
	public function delete($id) {
		try {
			$entity = $this->em->getReference ( $this->entity, $id );
			if ($entity) {
				$this->em->remove ( $entity );
				$this->em->flush ();
				return $entity;
			}
			return null;
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public function getById($id) {
		try {
			$repository = $this->em->getRepository($this->entity );
			$criteria = array (
					"id" => $id
			);
			$entity = $repository->findOneBy ( $criteria );
			return $entity;
		} catch ( \Exception $e ) {
			return null;
		}
	}
	public function listAll($orderBy=false) {
		try {
			$order = array();
			if($orderBy)
				$order = array('name'=>'ASC');
				$repository = $this->em->getRepository ( $this->entity );
				$entities = $repository->findBy (array(), $order);
				return $entities;
		} catch ( \Exception $e ) {
			return null;
		}
	}
}