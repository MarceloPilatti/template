<?php
namespace Core;
abstract class Application{
    public static function run(){
        $url=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
        $routes=require_once __DIR__."/../app/config/routes.php";
        $router=new Router($routes,$url);
        $router->route();
    }
}