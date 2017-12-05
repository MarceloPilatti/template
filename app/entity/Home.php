<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Home
 * @ORM\Table(name="HOME")
 * @ORM\Entity
 */
class Home{
	/**
	 *
	 * @var integer @ORM\Column(name="ID", type="integer", nullable=false)
	 *      @ORM\Id
	 *      @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	/**
	 *
	 * @var string @ORM\Column(name="TITLE", type="string", length=30, nullable=false)
	 */
	private $title;
	/**
	 *
	 * @var string @ORM\Column(name="SUBTITLE", type="string", length=100, nullable=false)
	 */
	private $subtitle;
	/**
	 *
	 * @var string @ORM\Column(name="ABOUT", type="text", nullable=false)
	 */
	private $about;
	public function getAttrs(){
		return get_object_vars($this);
	}
	public function __get($name){
		return $this->$name;
	}
	public function __set($name,$value){
		$this->$name=$value;
	}
	public function rules($id=''){
	    if($id){
	        $id=':'.$id;
	    }
	    return [
	        'título' => 'required|max:150',
            'subtítulo' => '',
	        'sobre nós' => 'required'
	    ];
	}
}

