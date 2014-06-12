<?php
$loginreq=0;
require('settings.php');
//require('logincheck.php');   J'ai annulé ici la vérification sinon le CAS me donnait une erreur de boucle de redirection
require('fonctions.php');

//CAS login

// Load the settings from the central config file
require_once 'config.php';
// Load the CAS lib
require_once $phpcas_path . '/CAS.php';

// Uncomment to enable debugging
phpCAS::setDebug();

// Initialize phpCAS
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);

// For production use set the CA certificate that is the issuer of the cert
// on the CAS server and uncomment the line below
// phpCAS::setCasServerCACert($cas_server_ca_cert_path);

// For quick testing you can disable SSL validation of the CAS server.
// THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
// VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
phpCAS::setNoCasServerValidation();

if (isset($_REQUEST['logout'])) {
    phpCAS::logout();
}
if (isset($_REQUEST['login'])) {
    phpCAS::forceAuthentication();
}

// check CAS authentication
$auth = phpCAS::checkAuthentication();

?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stages UNC</title>
    <link rel="stylesheet" href="scss/custom.css" />
    <script src="js/vendor/modernizr.js"></script>
</head>

<body>
<div class="row">
    <div class="large-12 columns">
        <h1>Objectif stages</h1>
    </div>
    <div class="large-12 columns">
        <?php
        //Authentification par le CAS
        if ($auth) {
            // for this test, simply print that the authentication was successfull
            ?>
            <h1>Successfull Authentication!</h1>
            <p>Nom d'utilisateur <b><?php echo phpCAS::getUser(); ?></b>.</p>
            <p><a href="?logout=">Se déconnecter</a></p><?php
        } else {
            // Demande d'authentification par le CAS
            ?>
            <h1>Guest mode</h1>
            <p><a href="?login=">Login</a></p><?php
        }
        ?>
        <p>phpCAS version is <b><?php echo phpCAS::getVersion(); ?></b>.</p>
    </div>
</div>


</body>
</html>