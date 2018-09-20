<?php
if(!session_id())session_start();
if(!file_exists(__DIR__.'/vendor/autoload.php')){
    echo "Vendor folder not found";
    exit();
}
require_once __DIR__.'/vendor/autoload.php';
setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');