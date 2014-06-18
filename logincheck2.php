<?php
    require('config.php');
    require($phpcas_path.'/CAS.php');
    phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
    phpCAS::setNoCasServerValidation();
    if (isset($_REQUEST['logout'])) {
        phpCAS::logout();
    }
    if (isset($_REQUEST['login'])) {
        phpCAS::forceAuthentication();
    }
    $auth = phpCAS::checkAuthentication();
    if ($auth) {
        $_SESSION["connected"]="etud";
    }
    if (isset($needConnexion)) {
        include("all.header2.php");
        echo '<h1>Vous devez vous connecter pour accèder à cette page!</h1>';
        include("all.footer2.php");
        die();
    }
?>