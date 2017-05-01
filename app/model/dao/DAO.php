<?php
class DAO {
	protected $entity;
	protected $em;
	protected $tableName;
	public function __construct() {
		global $entityManager;
		$this->em = $entityManager;
	}
	
	public function insert($entity){
		$sql = $this->getInsertSQL($entity);
		return $this->executeSql($sql, true);
	}
	
	public function update($entity){
		$sql = $this->getUpdateSQL($entity);
		return $this->executeSql($sql, false);
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
			$criteria = array ("id" => $id);
			$entity = $repository->findOneBy ( $criteria );
			return $entity;
		} catch ( \Exception $e ) {
			return null;
		}
	}
	
	public function listAll() {
		try {
			$repository = $this->em->getRepository ( $this->entity );
			$entities = $repository->findAll ();
			return $entities;
		} catch ( \Exception $e ) {
			return null;
		}
	}
	
	public function getInsertFieldNames($entity, $objectArray){
		$sql = "";
		$count = 0;
		foreach($objectArray as $name => $value) {
			$metadata = $this->em->getClassMetadata(get_class($entity));
			if($metadata->hasField($name)){
				$columnName = $metadata->getFieldMapping($name)["columnName"];
			}else{
				$columnName = $metadata->getAssociationMapping($name)["joinColumns"][0]["name"];
			}
			$sql .= " `".$columnName."`";
			if(count($objectArray) !== $count+1){
				$sql .= ",";
			}
			$count++;
		}
		return $sql;
	}
	
	public function getInsertFieldValues($entity, $objectArray){
		$count = 0;
		$sql = "";
		foreach ( $objectArray as $value ) {
			$value = $this->formatFields($value);
			$sql .= $value;
			if (count ( $objectArray ) !== $count + 1){
				$sql .= ",";
			}
			$count ++;
		}
		return $sql;
	}
	
	public function getUpdateFields($entity, $objectArray){
		$sql = "";
		$count = 0;
		foreach($objectArray as $name => $value) {
			$metadata = $this->em->getClassMetadata(get_class($entity));
			if($metadata->hasField($name)){
				$columnName = $metadata->getFieldMapping($name)["columnName"];
			}else{
				$columnName = $metadata->getAssociationMapping($name)["joinColumns"][0]["name"];
			}
			$value = $this->formatFields($value);
			
			$sql .= " `".$columnName."` = " . $value;
			
			if(count($objectArray) !== $count+1){
				$sql .= ",";
			}
			$count++;
		}
		return $sql;
	}
	
	public function getInsertSQL($entity){
		$metadata = $this->em->getClassMetadata(get_class($entity));
		$tableName = $metadata->getTableName();
		$objectArray = $entity->getAttrs();
		$sql = "INSERT INTO `" . $tableName . "` (";
		$sql .= $this->getInsertFieldNames($entity, $objectArray);
		$sql .= ") VALUES (";
		$sql .= $this->getInsertFieldValues($entity, $objectArray);
		$sql .= ");";
		return $sql;
	}
	
	public function getUpdateSQL($entity){
		$metadata = $this->em->getClassMetadata(get_class($entity));
		$tableName = $metadata->getTableName();
		$objectArray = $entity->getAttrs();
		$sql = "UPDATE `" . $tableName . "` SET ";
		$sql .= $this->getUpdateFields($entity, $objectArray);
		$sql .= " WHERE ID = ".$entity->id.";";
		return $sql;
	}
	
	public function formatFields($value){
		if (is_string ( $value )) {
			$value = "'" . $value . "'";
		}
		if (is_a($value, 'DateTime')){
			$value = "'" . $value->format ( "Y-m-d H:i:s" ) . "'";
		}
		if (is_object($value) && $value->id){
			$value = $value->id;
		}
		if (is_null ( $value )){
			$value = 'null';
		}
		return $value;
	}
	
	public function executeSql($sql, $insert){
		$conn = $this->em->getConnection ();
		try {
			$conn->exec ( $sql );
			if($insert){
				$id = $conn->lastInsertId();
				$entity = $this->getById($id);
				return $entity;
			}
			return true;
		} catch ( \Doctrine\DBAL\DBALException $dbalExc ) {
			return false;
		} catch ( \Exception $e ) {
			return false;
		}
	}
}