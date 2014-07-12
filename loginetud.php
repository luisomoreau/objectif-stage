<?php
require('settings.php');
require('fonctions.php');
require('./CAS.php');
phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
phpCAS::setNoCasServerValidation();
phpCAS::forceAuthentication();
$auth = phpCAS::checkAuthentication();
if ($auth) {
    $_SESSION['identifiant']=phpCAS::getUser();
    $_SESSION["connected"]="etud";

    $user = new LdapInfos($_SESSION['identifiant']);
    $mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);

    if (!($stmt = $mysqli->prepare('INSERT INTO etudiants (userEtud, mailEtud, civiliteEtud, nomEtud, prenomEtud,anneeEtud,filiereEtud,telEtud) VALUES (?,?,?,?,?,?,?,?)
ON DUPLICATE KEY UPDATE mailEtud=?, civiliteEtud=?, nomEtud=?, prenomEtud=?,anneeEtud=?,filiereEtud=?,telEtud=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }

//    if (!($stmt = $mysqli->prepare('UPDATE etudiants SET mailEtud=?, civiliteEtud=?, nomEtud=?, prenomEtud=?,anneeEtud=?,filiereEtud=?,telEtud=? WHERE userEtud=?'))) {
//        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
//    }
    $stmt->bind_param('sssssssssssssss', $_SESSION['identifiant'], $user->mail, $user->civilite, $user->nom, $user->prenom, $user->annee, $user->filiere, $user->telephone, $user->mail, $user->civilite, $user->nom, $user->prenom, $user->annee, $user->filiere, $user->telephone);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    header('location: ./');
    die();
}