<?php
abstract class ErrorType {
	const ERROR = 1;
	const NOTFOUND = 2;
}
class ApplicationError {
	public static function showError($throwable, $type) {
		if ($type == ErrorType::NOTFOUND) {
			$view = new View ( '../app/view/error/not-found-error-page.phtml' );
		} else if ($type == ErrorType::ERROR) {
			$message = null;
			if (getenv ( "APPLICATION_ENV" ) == "development") {
				if($throwable){
					$message = "<strong style='font-size:18px'>An error occurred: " . $throwable->getMessage () . " on file " . $throwable->getFile () . ", line " . $throwable->getLine () . "</strong><br /><br />";
					$trace = $throwable->getTrace ();
					$message .= "<table class='table table-bordered nomargin'><thead><th>File</th><th>Line</th><th>Action</th><th>Class</th><th>Type</th></thead></tbody>";
					if($trace){
						foreach ( $trace as $row ) {
							$message .= "<tr>";
							foreach ( $row as $cell ) {
								if (is_array ( $cell )) continue;
								$message .= "<td>" . $cell . "</td>";
							}
							$message .= "</tr>";
						}
					}
					$message .= "</tbody></table>";
				}
			}
			$view = new View ( '../app/view/error/error-page.phtml' );
			$view->params = array ("message" => $message);
		}
		$view->showContent ();
	}
}