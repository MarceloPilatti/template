<?php
namespace App\Service;

use Core\Session;

abstract class SessionService{
    public static function setPageHeader($pageTitle,$pageDescription,$pageRobots,$selectedItem){
        Session::set('selectedItem', $selectedItem);
        Session::set('pageTitle', $pageTitle);
        Session::set('pageDescription', $pageDescription);
        Session::set('pageRobots', $pageRobots);
    }
}