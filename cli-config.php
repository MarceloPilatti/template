<?php
require_once 'init.php';
$helperSet=new \Symfony\Component\Console\Helper\HelperSet(array(
    'em'=>new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
));
return $helperSet;