<?php
namespace App\dao;
use Core\DAO;
class SocialDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "App\\Entity\\Social";
        $this->tableName = "SOCIAL";
    }
}