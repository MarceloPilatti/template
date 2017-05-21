<?php
namespace entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`ROLE_PRIVILEGE`")
 * @ORM\Entity(repositoryClass="entity\RolePrivilegeRepository")
 * @ORM\Entity
 */
class RolePrivilege
{
    /**
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="entity\Privilege")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PRIVILEGE_ID", referencedColumnName="id")
     * })
     */
    private $privilege;

    /**
     * @ORM\ManyToOne(targetEntity="entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ROLE_ID", referencedColumnName="id")
     * })
     */
    private $role;

    
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