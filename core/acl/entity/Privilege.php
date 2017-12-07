<?php
namespace Core\Acl\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="`PRIVILEGE`")
 * @ORM\Entity(repositoryClass="Core\Acl\Entity\RolePrivilegeRepository")
 * @ORM\Entity
 */
class Privilege{
    /**
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @ORM\Column(name="`NAME`", type="string", length=255, nullable=false)
     */
    private $name;
    public function getAttrs(){
        return get_object_vars($this);
    }
    public function __get($name){
        return $this->$name;
    }
    public function __set($name,$value){
        $this->$name=$value;
    }
}