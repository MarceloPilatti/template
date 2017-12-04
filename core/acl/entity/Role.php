<?php
namespace Core\Acl\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`ROLE`")
 * @ORM\Entity(repositoryClass="Core\Acl\Entity\RoleRepository")
 * @ORM\Entity
 */
class Role
{
    /**
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="`NAME`", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(name="`IS_ADMIN`", type="boolean", nullable=false)
     */
    private $isAdmin;

    public function getAttrs(){
    	return get_object_vars($this);
    }

    public function __get($name) {
    	return $this->$name;
    }

    public function __set($name, $value) {
    	$this->$name = $value;
    }
}