<?php
namespace App\dao;
use Core\DAO;
class FooterAddressDAO extends DAO{
    public function __construct($dBName=null)
    {
        parent::__construct($dBName);
        $this->entity = "App\\Entity\\FooterAddress";
        $this->tableName = "FOOTER_ADDRESS";
    }
}