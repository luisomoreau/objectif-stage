<?php
require_once('settings.php');
require_once('fonctions.php');

// Load the settings from the central config file
require_once 'config.php';
// Load the CAS lib
require_once 'CAS.php';
// Initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
// For quick testing you can disable SSL validation of the CAS server.
// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
phpCAS::setNoCasServerValidation();

phpCAS::forceAuthentication();
$auth = phpCAS::checkAuthentication();
if ($auth) {
    casLogin();
}