<?php
namespace Core;
class Filter{
	public function filterString($var){
		if($var) return filter_var($var, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_EMPTY_STRING_NULL);
	}
	public function filterHtml($var){
		if($var) return filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
	}
	public function filterUrl($var){
		if($var) return filter_var($var, FILTER_SANITIZE_URL);
	}
	public function filterEmail($var){
		if($var) return filter_var($var, FILTER_SANITIZE_EMAIL);
	}
	public function filterRegex($name, $regex) {
		$matches = array ();
		$validatedName = preg_match ( $regex, $name, $matches );
		if ($validatedName == 1) return $matches[0];
		return $name;
	}
}