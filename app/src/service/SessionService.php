<?php
namespace App\Service;

use Core\Session;

abstract class SessionService{
    public static function setPageHeader($pageConfig=null,$page=null){
        Session::set('pageConfig', $pageConfig);
        Session::set('page', $page);
    }
}