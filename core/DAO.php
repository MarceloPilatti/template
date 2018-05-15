<?php
namespace Core;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;
abstract class DAO{
    protected $entity;
    protected $em;
    protected $tableName;
    public function __construct(){
        global $entityManager;
        $this->em=$entityManager;
    }
    public function insert($entity){
        $sql=$this->getInsertSQL($entity);
        return $this->executeSql($sql,true);
    }
    public function update($entity){
        $sql=$this->getUpdateSQL($entity);
        return $this->executeSql($sql,false);
    }
    public function delete($id){
        try{
            $entity=$this->em->getReference($this->entity,$id);
            if($entity){
                $this->em->remove($entity);
                $this->em->flush();
                return $entity;
            }
            return null;
        }catch(ORMException $oRME){
            return null;
        }catch(ORMInvalidArgumentException $iAE){
            return null;
        }
    }
    public function deleteBy($fieldName,$fieldValue){
        try{
            $entites=$this->getBy($fieldName, $fieldValue, true);
            foreach ($entites as $entity){
                $entity=$this->em->getReference(get_class($entity),$entity->id);
                if($entity){
                    $this->em->remove($entity);
                }
            }
            $this->em->flush();
            return true;
        }catch(ORMException $oRME){
            return false;
        }catch(ORMInvalidArgumentException $iAE){
            return false;
        }
    }
    public function getBy($fieldName,$fieldValue,$all=false){
        try{
            if(is_array($fieldName) && is_array($fieldValue)){
                $count=0;
                $fieldNameCount=count($fieldName);
                while($count<$fieldNameCount){
                    $criteria[$fieldName[$count]]=$fieldValue[$count];
                    $count++;
                }
            }else{
                $criteria=array($fieldName=>$fieldValue);
            }
            $repository=$this->em->getRepository($this->entity);
            if($all){
                $entity=$repository->findBy($criteria);
            }else{
                $entity=$repository->findOneBy($criteria);
            }
            return $entity;
        }catch(ORMException $oRME){
            return null;
        }
    }
    public function getById($id){
        try{
            return $this->getBy('id', $id);
        }catch(ORMException $oRME){
            return null;
        }
    }
    public function getBySlug($slug){
        try{
            return $this->getBy('slug', $slug);
        }catch(ORMException $oRME){
            return null;
        }
    }
    public function count(){
        $sql="SELECT COUNT(*) FROM ".$this->tableName;
        $conn=$this->em->getConnection();
        try{
            $stmt=$conn->prepare($sql);
            $stmt->execute();
            $count=reset($stmt->fetchAll()[0]);
            return $count;
        }catch(DBALException $dbalExc){
            return false;
        }
    }
    public function listAll($orderBy=array(),$order='ASC',$limit=null,$page=null){
        try{
            $offset=null;
            if($page)$offset=$limit*($page-1);
            if(!empty($orderBy)) $orderBy=array($orderBy=>$order);
            $repository=$this->em->getRepository($this->entity);
            $entities=$repository->findBy(array(),$orderBy,$limit,$offset);
            return $entities;
        }catch(ORMException $oRME){
            return null;
        }
    }
    public function getInsertFieldNames($entity,$objectArray){
        $sql="";
        $count=0;
        foreach($objectArray as $name=>$v){
            $metadata=$this->em->getClassMetadata(get_class($entity));
            if($metadata->hasField($name)){
                if($name=="id"){
                    $count++;
                    continue;
                }
                $columnName=$metadata->getFieldMapping($name)["columnName"];
            }else{
                $columnName=$metadata->getAssociationMapping($name)["joinColumns"][0]["name"];
            }
            $sql.=" `".$columnName."`";
            if(count($objectArray)!==$count+1){
                $sql.=",";
            }
            $count++;
        }
        return $sql;
    }
    public function getInsertFieldValues($entity,$objectArray){
        $count=0;
        $sql="";
        foreach($objectArray as $key=>$value){
        	if($key=='id'){
        		$count++;
        		continue;
        	}
            $value=$this->formatFields($value);
            $sql.=$value;
            if(count($objectArray)!==$count+1){
                $sql.=",";
            }
            $count++;
        }
        return $sql;
    }
    public function getUpdateFields($entity,$objectArray){
        $sql="";
        $count=0;
        foreach($objectArray as $name=>$value){
            $metadata=$this->em->getClassMetadata(get_class($entity));
            if($name=="id" || $name[0]=='_'){
                $count++;
                continue;
            }
            if($metadata->hasField($name)){
                $columnName=$metadata->getFieldMapping($name)["columnName"];
            }else{
                $columnName=$metadata->getAssociationMapping($name)["joinColumns"][0]["name"];
            }
            $value=$this->formatFields($value);
            $sql.=" `".$columnName."` = ".$value;
            if(count($objectArray)!==$count+1){
                $sql.=",";
            }
            $count++;
        }
        return $sql;
    }
    public function getInsertSQL($entity){
        $metadata=$this->em->getClassMetadata(get_class($entity));
        $tableName=$metadata->getTableName();
        $objectArray=$entity->getAttrs();
        $sql="INSERT INTO `".$tableName."` (";
        $sql.=$this->getInsertFieldNames($entity,$objectArray);
        $sql.=") VALUES (";
        $sql.=$this->getInsertFieldValues($entity,$objectArray);
        $sql.=");";
        return $sql;
    }
    public function getUpdateSQL($entity){
        $metadata=$this->em->getClassMetadata(get_class($entity));
        $tableName=$metadata->getTableName();
        $objectArray=$entity->getAttrs();
        $sql="UPDATE `".$tableName."` SET ";
        $sql.=$this->getUpdateFields($entity,$objectArray);
        $sql.=" WHERE ID = ".$entity->id.";";
        return $sql;
    }
    public function formatFields($value){
        if(is_string($value)){
            $value="'".$value."'";
        }
        if(is_a($value,'DateTime')){
            $value="'".$value->format("Y-m-d H:i:s")."'";
        }
        if(is_object($value)&&$value->id){
            $value=$value->id;
        }
        if(is_null($value)){
            $value='null';
        }
        return $value;
    }
    public function executeSql($sql,$insert){
        $conn=$this->em->getConnection();
        try{
            $conn->exec($sql);
            if($insert){
                $id=$conn->lastInsertId();
                $entity=$this->getById($id);
                return $entity;
            }
            return true;
        }catch(DBALException $dbalExc){
            return false;
        }
    }
    public function begin(){
    	$this->em->getConnection()->beginTransaction();
    }
    public function commit(){
    	$this->em->getConnection()->commit();
    }
    public function rollback(){
    	$this->em->getConnection()->rollback();
    }
}