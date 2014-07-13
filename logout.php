<?php
// Load the settings from the central config file
require_once 'config.php';
// Load the CAS lib
require_once $phpcas_path . '/CAS.php';
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

session_start();
if (isset ($_SESSION['connected'])) {
    if ($_SESSION['connected']=="etud") {
        session_destroy();
        phpCAS::logout();
    }
}
session_destroy();
header("Location: ./");
?>