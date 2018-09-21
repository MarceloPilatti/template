<?php
namespace App\dao;
use Core\DAO;
class FooterLinkDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "App\\Entity\\FooterLink";
        $this->tableName = "FOOTER_LINK";
    }
}