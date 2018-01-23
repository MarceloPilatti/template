<?php
namespace Core;
abstract class Validator{
    public static function make(array $data,array $rules){
        $errors=null;
        foreach($rules as $ruleKey=>$ruleValue){
            foreach($data as $dataKey=>$dataValue){
                if($ruleKey==$dataKey){
                    $itemsValue=[];
                    if(strpos($ruleValue,"|")){
                        $itemsValue=explode("|",$ruleValue);
                        foreach($itemsValue as $itemValue){
                            $subItems=[];
                            if(strpos($itemValue,":")){
                                $subItems=explode(":",$itemValue);
                                switch($subItems[0]){
                                    case 'min':
                                        if(strlen($dataValue)<$subItems[1])
                                            $errors["$ruleKey"]="O campo {$ruleKey} deve ter um mínimo de {$subItems[1]} caracteres.";
                                        break;
                                    case 'max':
                                        if(strlen($dataValue)>$subItems[1])
                                            $errors["$ruleKey"]="O campo {$ruleKey} deve ter um máximo de {$subItems[1]} caracteres.";
                                        break;
                                    case 'unique':
                                        $entityDAO="\\App\\DAO\\".$subItems[1]."DAO";
                                        $field=ucfirst($subItems[2]);
                                        $dao=new $entityDAO();
                                        $entity=$dao->getBy.$field.($dataValue);
                                        if($entity){
                                            if(isset($subItems[3])&&$entity->id==$subItems[3]){
                                                break;
                                            }else{
                                                $errors["$ruleKey"]="{$ruleKey} já registrado no banco de dados.";
                                                break;
                                            }
                                        }
                                        break;
                                }
                            }else{
                                switch($itemValue){
                                    case 'required':
                                        if($dataValue==' '||empty($dataValue))
                                            $errors["$ruleKey"]="O campo {$ruleKey} deve ser preenchido.";
                                        break;
                                    case 'email':
                                        if(!filter_var($dataValue,FILTER_VALIDATE_EMAIL))
                                            $errors["$ruleKey"]="O campo {$ruleKey} não é válido.";
                                        break;
                                    case 'float':
                                        if(!filter_var($dataValue,FILTER_VALIDATE_FLOAT))
                                            $errors["$ruleKey"]="O campo {$ruleKey} deve conter número decimal.";
                                        break;
                                    case 'int':
                                        if(!filter_var($dataValue,FILTER_VALIDATE_INT))
                                            $errors["$ruleKey"]="O campo {$ruleKey} deve conter número inteiro.";
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                    }elseif(strpos($ruleValue,":")){
                        $items=explode(":",$ruleValue);
                        switch($items[0]){
                            case 'min':
                                if(strlen($dataValue)<$items[1])
                                    $errors["$ruleKey"]="O campo {$ruleKey} deve ter um mínimo de {$items[1]} caracteres.";
                                break;
                            case 'max':
                                if(strlen($dataValue)>$items[1])
                                    $errors["$ruleKey"]="O campo {$ruleKey} deve ter um máximo de {$items[1]} caracteres.";
                                break;
                            case 'unique':
                                $entityDAO="\\App\\DAO\\".$subItems[1]."DAO";
                                $field=ucfirst($subItems[2]);
                                $dao=new $entityDAO();
                                $entity=$dao->getBy.$field.($dataValue);
                                if($entity){
                                    if(isset($subItems[3])&&$entity->id==$subItems[3]){
                                        break;
                                    }else{
                                        $errors["$ruleKey"]="{$ruleKey} já cadastrado.";
                                        break;
                                    }
                                }
                                break;
                        }
                    }else{
                        switch($ruleValue){
                            case 'required':
                                if($dataValue==' '||empty($dataValue))
                                    $errors["$ruleKey"]="O campo {$ruleKey} deve ser preenchido.";
                                break;
                            case 'email':
                                if(!filter_var($dataValue,FILTER_VALIDATE_EMAIL))
                                    $errors["$ruleKey"]="O campo {$ruleKey} não é válido.";
                                break;
                            case 'float':
                                if(!filter_var($dataValue,FILTER_VALIDATE_FLOAT))
                                    $errors["$ruleKey"]="O campo {$ruleKey} deve conter número decimal.";
                                break;
                            case 'int':
                                if(!filter_var($dataValue,FILTER_VALIDATE_INT))
                                    $errors["$ruleKey"]="O campo {$ruleKey} deve conter número inteiro.";
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
        if($errors){
            Session::setErrors($errors);
            Session::setInputs($data);
            return true;
        }else{
            Session::unset(['errors','inputs']);
            return false;
        }
    }

    public static function validateFile($files,$validExtensions)
    {
        try {
            $error=null;
            $fileName=$files['name'];
            $tempName=$files['tmp_name'];
            $fileSize=$files['size'];
            $fileExtension=pathinfo($fileName,PATHINFO_EXTENSION);
            $msg = true;
            $postMaxSize = ini_get('upload_max_filesize');
            $postMaxSizeBytes = Validator::return_bytes($postMaxSize);
            if ($error) {
                switch ($error) {
                    case UPLOAD_ERR_INI_SIZE:
                        $msg = "O tamanho do documento ".$fileName." excede o limite de ".$postMaxSize."B.";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $msg = "O tamanho do documento ".$fileName." excede o limite de ".$postMaxSize."B.";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $msg = 'O upload do arquivo foi feito parcialmente.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $msg = 'Nenhum arquivo foi enviado.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $msg = 'Pasta temporária ausênte.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $msg = 'Falha em escrever o arquivo em disco.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $msg = 'Uma extensão do PHP interrompeu o upload do arquivo.';
                        break;
                }
            }
            if (empty($fileName)&&empty($tempName)) {
                $msg = "Nenhum documento foi selecionado.";
                return $msg;
            }
            if ($fileSize<=0||$fileSize>$postMaxSizeBytes) {
                $msg = "O tamanho do documento ".$fileName." excede o limite de ".$postMaxSize."B.";
                return $msg;
            }
            if (!in_array($fileExtension, $validExtensions)) {
                $msg = "A extensão do documento ".$fileName." é inválida.";
                return $msg;
            }
            return $msg;
        } catch (\Throwable $t) {
            return false;
        }
    }

    public static function return_bytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    public static function rearrangeArrayFiles(&$files)
    {
        $fileList = array();
        $fileCount = count($files['name']);
        $fileKeys = array_keys($files);
        for ($i = 0; $i<$fileCount; $i++) {
            foreach ($fileKeys as $key) {
                $fileList[$i][$key] = $files[$key][$i];
            }
        }
        return $fileList;
    }
}