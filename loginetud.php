<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require($phpcas_path.'/CAS.php');
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
$auth = phpCAS::checkAuthentication();
if ($auth) {
    $_SESSION['identifiant']=phpCAS::getUser();
    $_SESSION["connected"]="etud";
    header('location: ./');
    die();
}