<?php
class FlashMessage{
	public static function setMessage($message, $type){
		if(!empty($_SESSION["message"])){
			unset($_SESSION["message"]);
		}
		if(!empty($_SESSION["class"])){
			unset($_SESSION["class"]);
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
		$_SESSION["message"] = $message.".";
		$_SESSION["class"] = $class;
	}
	public static function show(){
		if(!empty($_SESSION["message"]) && !empty($_SESSION["class"])){
			$message = $_SESSION["message"];
			$class = $_SESSION["class"];
			echo "<div class='flash alert $class'>$message<button class='close' data-dismiss='alert' aria-hidden='true'>&times;</button></div>";
			unset($_SESSION["message"]);
			unset($_SESSION["class"]);
		}
	}
}