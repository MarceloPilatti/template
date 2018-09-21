<?php
namespace App\dao;
use Core\DAO;
class FooterColumnDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "App\\Entity\\FooterColumn";
        $this->tableName = "FOOTER_COLUMN";
    }
}