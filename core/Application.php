<?php
namespace Core;

abstract class Application{
	public static function run(){
	    $router=new Router();
	    $router->route();
	}
}