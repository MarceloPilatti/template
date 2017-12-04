<?php
namespace Core;
abstract class LogLevel{
	const INFO = 1;
	const WARNING = 2;
	const ERROR = 3;
}
class Log{
	public static function logMessage($msg, $level = LogLevel::INFO, $file = 'log' ){
		$levelStr = '';
		if ($level==LogLevel::INFO){
		    $levelStr = 'INFO';
		}else if ($level==LogLevel::WARNING){
		    $levelStr = 'WARNING';
	    }else if ($level==LogLevel::ERROR){
	        $levelStr = 'ERROR';
	    }
		$date = date( 'Y-m-d H:i:s' );
		$msg = sprintf("[%s] [%s]: %s%s", $date, $levelStr, $msg, PHP_EOL);
		file_put_contents( $file, $msg, FILE_APPEND );
	}
}