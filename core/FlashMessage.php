<?php
namespace Core;
abstract class FlashType {
    const SUCCESS = 1;
    const ERROR = 2;
    const WARNING = 3;
    const INFO = 4;
}
abstract class FlashMessage{
	public static function setMessage($message, $type){
		if(Session::get('message')){
		    Session::unset('message');
		}
		if(Session::get('class')){
		    Session::unset('class');
		}
		switch ($type){
			case FlashType::SUCCESS:
				$class = "alert-success";
				break;
			case FlashType::ERROR:
				$class = "alert-danger";
				break;
			case FlashType::WARNING:
				$class = "alert-warning";
				break;
			case FlashType::INFO:
				$class = "alert-info";
				break;
		}
		Session::set('message', $message.'.');
		Session::set('class', $class);
	}
	public static function show(){
	    if(Session::get('message') && Session::get('class')){
	        $message=Session::get('message');
	        $class=Session::get('class');
			echo "<div class='flash alert ".$class."'>".$message."<button class='close' data-dismiss='alert' aria-hidden='true'>&times;</button></div>";
			Session::unset(['message','class']);
		}
	}
}