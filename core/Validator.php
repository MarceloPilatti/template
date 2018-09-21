<?php
namespace Core;

use Intervention\Image\ImageManagerStatic as Image;
use DateTime;

class Validator
{
    private $formData;
    private $entityRules;
    private $entityNames;
    private $entityId;
    private $dBName;
    private $entities=[];
    public function __construct($formData, $entityRules, $entityNames, $entityId, $dBName){
        $this->formData=$formData;
        $this->entityRules=$entityRules;
        $this->entityNames=$entityNames;
        $this->entityId=$entityId;
        $this->dBName=$dBName;
    }
    public function validateForm(){
        $errors=null;
        $inputs=null;
        $entityRulesArray=$this->entityRules;
        $entityNamesArray=$this->entityNames;
        $dBName=$this->dBName;
        $entityId=$this->entityId;
        $formdata=$this->formData;
        $fKEntityId=null;
        $entityMultipleFiles=[];
        if(!is_array($entityRulesArray)){
            $entityRulesArray=[$entityRulesArray];
        }
        $entityValues=[];
        foreach ($entityRulesArray as $count=>$entityRuleArray){
            $entityName=$entityNamesArray[$count];
            $entityClass=substr(strrchr($entityName, "\\"), 1);
            $entityDAOName='App\\DAO\\'.$entityClass.'DAO';
            $entityClass=strtolower($entityClass);
            $entityDAO=new $entityDAOName;
            $entityDAO->dBName=$dBName;
            $entity=$entityDAO->getById($entityId);
            if($entity){
                $entityValues=$entity->getAttrs();
            }
            if(!$entity){
                $entity=new $entityName;
            }
            foreach ($entityRuleArray as $ruleKey=>$entityRule){
                $rules=$entityRule["rules"];
                if(strpos($rules,"|")!==false){
                    $rulesArray=explode("|",$rules);
                }else{
                    $rulesArray=[$rules];
                }
                if(!array_key_exists($ruleKey, $formdata) && !$fKEntityId){
                    $isDefault=false;
                    foreach ($rulesArray as $rule){
                        if(strpos($rule, (RuleType::DEFAULT)!==false || strpos($rule, RuleType::DATE)!==false || strpos($rule, RuleType::DATETIME)!==false) && !$entityId){
                            $isDefault=true;
                            break;
                        }
                    }
                    if(!$isDefault){
                        continue;
                    }
                }
                $data=$formdata[$ruleKey];
                $msgError="";
                $entityValues[$ruleKey]=$data;
                foreach ($rulesArray as $rule){
                    if(strpos($rule,":")!==false){
                        $ruleInfo=explode(":",$rule);
                        $ruleDesc=$ruleInfo[0];
                        $ruleValue=$ruleInfo[1];
                        if($ruleDesc===RuleType::MIN){
                            $msgError.=Validator::validateMaxMin($data, $ruleValue, RuleType::MIN);
                        }else if($ruleDesc===RuleType::MAX){
                            $msgError.=Validator::validateMaxMin($data, $ruleValue, RuleType::MAX);
                        }else if($ruleDesc===RuleType::DEFAULT){
                            $entityValues[$ruleKey]=$ruleValue;
                        }else if($ruleDesc===RuleType::NORMAL_CHARS){
                            $specialCharsValidated=Validator::validateSpecialChars($data, $ruleValue);
                            $msgError.=$specialCharsValidated['msgError'];
                            $entityValues[$ruleKey]=$specialCharsValidated['data'];
                        }else if($ruleDesc===RuleType::FOREIGN_KEY){
                            if($ruleValue==='one'){
                                if(!$data){
                                    $data=$fKEntityId;
                                }
                                $entityValues[$ruleKey]=$data;
                            }else if($ruleValue==='many'){
                                $ids=$data;
                                $many=1;
                                $entityForeignKeys=[];
                                foreach ($ids as $count=>$id){
                                    $entityForeignKeys[$ruleKey][$count]=[$ruleKey=>$id];
                                }
                                $entityValues[$ruleKey]=null;
                            }
                        }
                    }else{
                        switch ($rule){
                            case RuleType::REQUIRED:
                                $msgError.=Validator::validateRequired($data);
                                break;
                            case RuleType::UNIQUE:
                                $msgError.=Validator::validateUnique($data, $entityDAO, $entityId, $ruleKey);
                                break;
                            case RuleType::FLOAT:
                                $isFloatValidated=Validator::validateFloat($data);
                                $msgError.=$isFloatValidated['msgError'];
                                $entityValues[$ruleKey]=$isFloatValidated['data'];
                                break;
                            case RuleType::INT:
                                $isIntValidated=Validator::validateInt($data);
                                $msgError.=$isIntValidated['msgError'];
                                $entityValues[$ruleKey]=$isIntValidated['data'];
                                break;
                            case RuleType::EMAIL:
                                $data=trim($data);
                                $msgError.=Validator::validateEmail($data);
                                $entityValues[$ruleKey]=$data;
                                break;
                            case RuleType::HTML:
                                if(strpos($rulesArray,"required")!==false){
                                    if(!$data || $data==='<p>&nbsp;</p>'){
                                        $msgError.="Este campo é obrigatório.<br />";
                                    }
                                }
                                $entityValues[$ruleKey]=htmlentities($data);
                                break;
                            case RuleType::DATETIME:
                                $dateValidated=Validator::validateDate($data, RuleType::DATETIME);
                                $msgError.=$dateValidated['msgError'];
                                $entityValues[$ruleKey]=$dateValidated['data'];
                                break;
                            case RuleType::DATE:
                                $dateValidated=Validator::validateDate($data, RuleType::DATE);
                                $msgError.=$dateValidated['msgError'];
                                $entityValues[$ruleKey]=$dateValidated['data'];
                                break;
                            case RuleType::PHONE:
                                $data=trim($data);
                                $phone=$data;
                                $phone = str_replace(['(', ')', ' ', '-'], "", $phone);
                                $entityValues[$ruleKey]=$phone;
                                break;
                            case RuleType::MONEY:
                                $money=$data;
                                $money = str_replace(['R$ ', '.'], "", $money);
                                $money = str_replace(',', ".", $money);
                                $entityValues[$ruleKey]=$money;
                                break;
                            case RuleType::PASSWORD:
                                $data=trim($data);
                                $password=$data;
                                $passwordHash=password_hash($password, PASSWORD_DEFAULT);
                                $entityValues[$ruleKey]=$passwordHash;
                                break;
                            case RuleType::CONFIRM:
                                $fieldData=trim($data);
                                $fieldKey=$ruleKey;
                                $confirmFieldName='confirm'.ucfirst($fieldKey);
                                if(array_key_exists($confirmFieldName, $formdata)){
                                    $confirmFieldData=$formdata[$confirmFieldName];
                                    if($fieldData!==$confirmFieldData){
                                        $msgError.='Os valores não conferem.';
                                    }
                                    $inputs[$confirmFieldName]=$confirmFieldData;
                                }
                                break;
                            case RuleType::FILE:
                                $validExtensions=$entityRule["extensions"];
                                $multipleFile=$entityRule["multiple"];
                                if(strpos($ruleValue, ';')){
                                    $validExtensions=explode(";",$ruleValue);
                                }
                                $files=$data[$ruleKey];
                                if(!$multipleFile && !$files['name'] && $entityId && $entity->filePath){
                                    $entityValues['fileName']=$entity->fileName;
                                    $entityValues['filePath']=$entity->filePath;
                                    break;
                                }
                                if(is_array($files)){
                                    if(isset($files['name'])){
                                        $files=[$files];
                                    }
                                    foreach ($files as $count=>$file){
                                        $isValid=Validator::validateFile($file, $validExtensions);
                                        if($isValid!==true){
                                            $msgError.=$isValid;
                                        }
                                        $fileOriginalName=$file['name'];
                                        $tempName=$file['tmp_name'];
                                        $fullDestPath='./data/uploads/'.$entityClass.'/';
                                        if(!is_dir($fullDestPath)){
                                            mkdir($fullDestPath, 0777, true);
                                        }
                                        $isImage=$entityRule["isImage"];
                                        if($isImage==1){
                                            $fullDestThumbPath=$fullDestPath.'thumb/';
                                            if(!is_dir($fullDestThumbPath)){
                                                mkdir($fullDestThumbPath, 0777, true);
                                            }
                                        }
                                        $filePath=$fullDestPath;
                                        $fileExtension=pathinfo($file['name'],PATHINFO_EXTENSION);
                                        $fileName=md5(uniqid(rand(), true).time()).'.'.$fileExtension;
                                        $destFileName=$fullDestPath.$fileName;
                                        $destThumbName=$fullDestThumbPath.$fileName;
                                        $imgSize=$entityRule["size"];
                                        if($isImage==1){
                                            $img = Image::make($tempName);
                                            if($imgSize){
                                                $imgSize=explode('x', $imgSize);
                                                $width=$imgSize[0];
                                                $height=$imgSize[1];
                                                $img->fit($width, $height);
                                            }
                                            $img=$img->save($destFileName,75);
                                            if(!$img){
                                                $msgError.='Erro ao salvar a imagem.<br />';
                                            }
                                            $img = Image::make($tempName)->fit(100, 100)->save($destThumbName,70);
                                            if(!$img){
                                                $msgError.='Erro ao salvar a miniatura.<br />';
                                            }
                                        }else{
                                            move_uploaded_file($tempName, $destFileName);
                                        }
                                        if($multipleFile){
                                            $entityMultipleFiles[$count]['fileOriginalName']=$fileOriginalName;
                                            $entityMultipleFiles[$count]['fileName']=$fileName;
                                            $entityMultipleFiles[$count]['filePath']=$filePath;
                                            $entityValues['fileOriginalName']=null;
                                            $entityValues['fileName']=null;
                                            $entityValues['filePath']=null;
                                        }else{
                                            $entityValues['fileOriginalName']=$fileOriginalName;
                                            $entityValues['fileName']=$fileName;
                                            $entityValues['filePath']=$fullDestPath;
                                        }
                                    }
                                }
                                break;
                            case RuleType::CPF:
                                $data=trim($data);
                                $cpf=$data;
                                if(!self::validateCPF($cpf)){
                                    $msgError.="CPF inválido.<br />";
                                }
                                $cpf = str_replace(['.','-'], "", $cpf);
                                $entityValues[$ruleKey]=$cpf;
                                break;
                            case RuleType::CNPJ:
                                $data=trim($data);
                                $cnpj=$data;
                                if(!self::validateCNPJ($cnpj)){
                                    $msgError.="CNPJ inválido.<br />";
                                }
                                $cnpj = str_replace(['.', '-', '/'], "", $cnpj);
                                $entityValues[$ruleKey]=$cnpj;
                                break;
                        }
                    }
                }
                if($msgError){
                    $errors[$ruleKey]=$msgError;
                }
                $inputs[$ruleKey]=$data;
            }
            if($errors){
                Session::set('errors', $errors);
                Session::set('inputs', $inputs);
                return 1;
            }else{
                Session::set('errors', null);
                Session::set('inputs', null);
                if($multipleFile){
                    unset($entityValues['file']);
                    $entities=[];
                    $entity=new $entityName;
                    if($entityMultipleFiles){
                        foreach ($entityMultipleFiles as $count=>$entityMultipleFile) {
                            $entityValues['fileOriginalName']=$entityMultipleFile['fileOriginalName'];
                            $entityValues['fileName']=$entityMultipleFile['fileName'];
                            $entityValues['filePath']=$entityMultipleFile['filePath'];
                            $entity->setAttrs($entityValues);
                            $entity=$entityDAO->insert($entity);
                            if(!$entity){
                                return 2;
                            }
                            $entities[$entityClass][$count]=$entity;
                        }
                    }
                }else if($many){
                    $entities=[];
                    if($entityForeignKeys){
                        foreach ($entityForeignKeys as $key=>$eFK) {
                            $count=0;
                            foreach ($eFK as $entityForeignKey){
                                $entityValues[$key]=$entityForeignKey[$key];
                                $entity->setAttrs($entityValues);
                                $entity=$entityDAO->insert($entity);
                                if(!$entity){
                                    return 2;
                                }
                                $entities[$entityClass][$count]=$entity;
                                $count++;
                            }
                        }
                    }
                }else{
                    $entity->setAttrs($entityValues);
                    if($entityId){
                        $result=$entityDAO->update($entity);
                        if(!$result){
                            return 2;
                        }
                    }else{
                        $entity=$entityDAO->insert($entity);
                        if(!$entity){
                            return 2;
                        }
                    }
                    $entityId=null;
                    $fKEntityId=$entity->id;
                    $entities[$entityClass]=$entity;
                }
            }
        }
        $this->entities=$entities;
        return true;
    }
    public static function validateFile($files,$validExtensions)
    {
        try {
            $error=null;
            $fileName=$files['name'];
            $tempName=$files['tmp_name'];
            $fileSize=$files['size'];
            $fileExtension=pathinfo($fileName,PATHINFO_EXTENSION);
            $validExtensions=explode(';', $validExtensions);
            $msg = true;
            $postMaxSize = ini_get('upload_max_filesize');
            $postMaxSizeBytes = self::return_bytes($postMaxSize);
            if ($error) {
                switch ($error) {
                    case UPLOAD_ERR_INI_SIZE:
                        $msg = "O tamanho do arquivo ".$fileName." excede o limite de ".$postMaxSize."B.";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $msg = "O tamanho do arquivo ".$fileName." excede o limite de ".$postMaxSize."B.";
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
                $msg = "Nenhum arquivo foi selecionado.";
                return $msg;
            }
            if ($fileSize<=0||$fileSize>$postMaxSizeBytes) {
                $msg = "O tamanho do arquivo ".$fileName." excede o limite de ".$postMaxSize."B.";
                return $msg;
            }
            if (!in_array(strtolower($fileExtension), $validExtensions)) {
                $msg = "A extensão do arquivo ".$fileName." é inválida.";
                return $msg;
            }
            return $msg;
        } catch (\Throwable $t) {
            return false;
        }
    }

    private static function return_bytes($val)
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

    private function validateCPF($cpf)
    {
        if (! $cpf) {
            return false;
        }
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);
        if (strlen($cpf) != 11) {
            return false;
        }
        $rest = 0;
        $sum = 0;
        if ($cpf == "12345678909" || $cpf == "00000000000" || $cpf == "11111111111" || $cpf == "22222222222" || $cpf == "33333333333" || $cpf == "44444444444" || $cpf == "55555555555" || $cpf == "66666666666" || $cpf == "77777777777" || $cpf == "88888888888" || $cpf == "99999999999") {
            return false;
        }
        for ($count = 1; $count <= 9; $count ++) {
            $digit = $cpf[$count - 1];
            $mult = intval($digit) * (11 - $count);
            $sum += $mult;
        }
        $rest = ($sum * 10) % 11;
        if (($rest == 10) || ($rest == 11)) {
            $rest = 0;
        }
        if ($rest != intval($cpf[9])) {
            return false;
        }
        $sum = 0;
        for ($count = 1; $count <= 10; $count ++) {
            $digit = $cpf[$count - 1];
            $mult = intval($digit) * (12 - $count);
            $sum += $mult;
        }
        $rest = ($sum * 10) % 11;
        if (($rest == 10) || ($rest == 11)) {
            $rest = 0;
        }
        if ($rest != intval($cpf[10])) {
            return false;
        }
        return true;
    }
    private function validateCNPJ($cnpj)
    {
        if (!$cnpj) {
            return false;
        }
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        if (strlen($cnpj) != 14){
            return false;
        }
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)){
            return false;
        }
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
    public static function validateMaxMin($data, $ruleValue, $ruleType){
        $msgError='';
        if($ruleType==RuleType::MIN){
            $data=trim($data);
            if($data<$ruleValue){
                $msgError.="São aceitos no mínimo ".$ruleValue." caracteres.<br />";
            }
        }else if($ruleType==RuleType::MAX){
            $data=trim($data);
            if($data>$ruleValue){
                $msgError.="São aceitos no máximo ".$ruleValue." caracteres.<br />";
            }
        }
        return $msgError;
    }
    public static function validateSpecialChars($data, $ruleValue){
        $result=[];
        $msgError='';
        $data=trim($data);
        $exceptions=$ruleValue;
        if(strpos($ruleValue, ';')){
            $exceptions=explode(";",$ruleValue);
        }
        $string=$data;
        $tempString=str_replace($exceptions, '', $string);
        if(preg_match('/[^a-zA-Z\d]/', $tempString)){
            $msgError.="Não são permitidos caracteres especiais";
            if($exceptions){
                $msgError.=" exceto ";
                foreach ($exceptions as $count=>$exception){
                    $msgError.=$exception;
                    if(count($exceptions)!==$count){
                        $msgError.=', ';
                    }
                }
            }
        }
        $result['data']=$string;
        $result['msgError']=$msgError;
        return $result;
    }
    public static function validateRequired($data){
        $msgError='';
        if(!$data){
            $msgError.="Este campo é obrigatório.<br />";
        }
        return $msgError;
    }
    public static function validateUnique($data, $entityDAO, $entityId, $ruleKey){
        $msgError='';
        $checkUnique=$entityDAO->getBy([$ruleKey=>$data], null, null, null, null, true);
        if($checkUnique && $checkUnique->id!=$entityId){
            $msgError.="Já cadastrado. Escolha outro.<br />";
        }
        return $msgError;
    }
    public static function validateFloat($data){
        $result=[];
        $msgError='';
        if($data){
            if(!is_int($data)){
                $msgError.="Digite somente números.<br />";
            }
        }
        $result['data']=$data;
        $result['msgError']=$msgError;
        return $result;
    }
    public static function validateInt($data){
        $result=[];
        $msgError='';
        if(!$data){
            $data='0';
        }
        if(!is_numeric($data)){
            $msgError.="Digite somente números.<br />";
        }
        $result['data']=$data;
        $result['msgError']=$msgError;
        return $result;
    }
    public static function validateEmail($data){
        $msgError='';
        $data=trim($data);
        if($data){
            if(!filter_var($data, FILTER_VALIDATE_EMAIL)){
                $msgError.="Email inválido.<br />";
            }
        }
        return $msgError;
    }
    public static function validateDate($data, $ruleType){
        $data=trim($data);
        $result=[];
        $msgError='';
        if($ruleType==RuleType::DATETIME){
            $inputFormat='d/m/Y H:i:s';
            $storeFormat='Y-m-d H:i:s';
        }else if($ruleType==RuleType::DATE){
            $inputFormat='d/m/Y';
            $storeFormat='Y-m-d';
        }
        if($data){
            $verifierDate = DateTime::createFromFormat($inputFormat, $data);
            if($verifierDate && $verifierDate->format($inputFormat) == $data){
                $msgError.="Data inválida.<br />";
            }
            $date=$data;
            $date = new \DateTime(str_replace('/', '-', $date), new \DateTimeZone("America/Sao_Paulo"));
            $data=$date->format($storeFormat);
        }else{
            $date = new \DateTime('now', new \DateTimeZone("America/Sao_Paulo"));
            $data=$date->format($storeFormat);
        }
        $result['data']=$data;
        $result['msgError']=$msgError;
        return $result;
    }
    public function __set($name, $value)
    {
        $this->$name=$value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
}