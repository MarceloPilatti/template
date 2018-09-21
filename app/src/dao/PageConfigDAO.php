<?php
namespace App\dao;
use Core\DAO;
class PageConfigDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "App\\Entity\\PageConfig";
        $this->tableName = "PAGE_CONFIG";
    }
}