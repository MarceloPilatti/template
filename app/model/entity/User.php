<?php
namespace entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="`USER`")
 * @ORM\Entity(repositoryClass="Entity\UserRepository")
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="USER_NAME", type="string", length=10, nullable=false)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="`PASSWORD`", type="string", length=10, nullable=false)
     */
    private $password;
    
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