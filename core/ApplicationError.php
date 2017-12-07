<?php
namespace Core;
abstract class ErrorType{
    const ERROR=1;
    const NOTFOUND=2;
}
abstract class ApplicationError{
    public static function showError($throwable,$type){
        if($type==ErrorType::NOTFOUND){
            return new View('error/not-found-error-page');
        }else if($type==ErrorType::ERROR){
            $message=null;
            if(getenv("APPLICATION_ENV")=="development"){
                if($throwable){
                    $message="<strong style='font-size:18px'>An error occurred: ".$throwable->getMessage()." on file ".$throwable->getFile().", line ".$throwable->getLine()."</strong><br /><br />";
                    $trace=$throwable->getTrace();
                    $message.="<div class='table-overflow'><table class='table table-bordered nomargin'><thead><th>File</th><th>Line</th><th>Action</th><th>Class</th><th>Type</th></thead></tbody>";
                    if($trace){
                        foreach($trace as $row){
                            $message.="<tr>";
                            foreach($row as $cell){
                                if(is_array($cell))continue;
                                $message.="<td>".$cell."</td>";
                            }
                            $message.="</tr>";
                        }
                    }
                    $message.="</tbody></table></div>";
                }
            }
            return new View('error/error-page',array("message"=>$message));
        }
    }
}