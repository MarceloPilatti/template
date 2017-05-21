<?php
class Validation{
	public function validateLength($min=0, $max=0){
		return $this->checkLength($min, $max);
	}
	public function validateString($var, $acceptNumbers=true, $min=null, $max=null){
		if($var && !is_object($var) && is_string($var)){
			if(!$acceptNumbers && is_numeric($var) || preg_match('#[0-9]#',$var)) return false;
			$lengthValidation = true;
			if($min || $max) $lengthValidation = $this->checkLength($var, $min, $max);
			return $lengthValidation;
		}
		return false;
	}
	public function validateNumber($var, $min=null, $max=null){
		if($var && !is_object($var) && is_numeric($var)){
			$lengthValidation = true;
			if($min || $max) $lengthValidation = $this->checkLength($var, $min, $max);
			return $lengthValidation;
		}
		return false;
	}
	public function validateInt($var){
		if($var) return filter_var($var, FILTER_VALIDATE_INT) === 0 || filter_var($var, FILTER_VALIDATE_INT) !== false;
	}
	public function validateFloat($var){
		if($var) return filter_var($var, FILTER_VALIDATE_FLOAT) !== false;
	}
	public function validateUrl($var){
		if($var) return filter_var($var, FILTER_VALIDATE_URL) !== false;
	}
	public function validateEmail($var){
		if($var) return filter_var($var, FILTER_VALIDATE_EMAIL) !== false;
	}
	public function validateIp($var){
		if($var) return filter_var($var, FILTER_VALIDATE_IP) !== false;
	}
	
	private function checkLength($var, $min=0, $max=0){
		if($var && !is_object($var) && ($min != 0 || $max != 0)){
			$len = mb_strlen($var);
			if((isset($min) && $len < $min) || (isset($max) && $len > $max)) return false;
			return true;
		}
		return false;
	}
	public function validateRegex($name, $regex) {
		$matches = array ();
		$validatedName = preg_match ( $regex, $name, $matches );
		if ($validatedName && $validatedName != 0) {
			if ($matches [0] == $name) return true;
		}
		return false;
	}
	private function rearrangeArrayFiles(&$files){
		$fileList = array();
		$fileCount = count($files['name']);
		$fileKeys = array_keys($files);
		for ($i=0; $i<$fileCount; $i++) {
			foreach ($fileKeys as $key) $fileList[$i][$key] = $files[$key][$i];
		}
		return $fileList;
	}
	
	public function validateUpload($files, $validExtensions) {
		$files = $this->rearrangeArrayFiles($files);
		if($files){
			foreach ($files as $file){
				$fileName = $file[name];
				$fileExtension = $file[type];
				$tempName = $file[tmp_name];
				$fileSize = $file[size];
				$validate = $this->validateFile($validExtensions, $fileSize, $fileName, $tempName, $fileExtension);
				if($validate !== true) return $validate;
			}
			return true;
		}
		return false;
	}
	private function validateFile($validExtensions,$fileSize,$fileName,$tempName,$fileExtension) {
		try {
			$msg = true;
			$postMaxSize = $this->return_bytes(ini_get('upload_max_filesize'));
			if(empty($fileName) && empty($tempName)){
				$msg = "Nenhum documento foi selecionado.";
				return $msg;
			}
			if($fileSize <= 0 || $fileSize > $postMaxSize){
				$msg = "O tamanho do documento ". $fileName ." excede o limite de ".$postMaxSize."B e não poderá ser enviado.";
				return $msg;
			}
			if(!in_array($fileExtension,$validExtensions)){
				$msg = "A extensão do documento ". $fileName ." é inválida.";
				return $msg;
			}
			return $msg;
		} catch (\Throwable $t) {
			return false;
		}
	}
	private function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':$val *= 1024;break;
			case 'm':$val *= 1024;break;
			case 'k':$val *= 1024;break;
		}
		return $val;
	}
}