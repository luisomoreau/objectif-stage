<?php
//include('nontraite.php');
session_start();
// Chargement des paramètres de la DB @todo SQL
setlocale(LC_TIME, 'fr','fr_FR','fr_FR@euro','fr_FR.utf8','fr-FR','fra');
// Connection SQL
$dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));

$redirect = basename($_SERVER["SCRIPT_FILENAME"], '.php');
if (!isset($loginreq)) {
    $loginreq=1;
}
if ($loginreq !== 0 || isset($_SESSION['identifiant']) ) {
    if (isset($_SESSION['identifiant']) && isset($_SESSION['mdp']) && isset($_SESSION['type'])) {
        // Requète SQL
        $login=$_SESSION['identifiant'];
        $mdp=$_SESSION['mdp'];
        $query = "SELECT COUNT(*) as 'existant', valideEtud, dateInscriptionEtud, prenomEtud, nomEtud FROM etudiants WHERE valideEtud='1' AND ((mailEtud = '".$login."' AND  mdpEtud = '".$mdp."') OR (mailPersoEtud = '".$login."' AND  mdpEtud = '".$mdp."'))";
        $query2 = "SELECT COUNT(*) as 'existant', nomEnt FROM entreprises WHERE valideEnt='1' AND (mailEnt = '".$login."' AND  mdpEnt = '".$mdp."')";
        $query3 = "SELECT COUNT(*) as 'existant', prenomAdmin, nomAdmin FROM administrateurs WHERE (mailAdmin = '".$login."' AND  mdpAdmin = '".$mdp."')";
        
        // Exécution de la requète
        $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
        $result2 = mysqli_query($dblink, $query2) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
        $result3 = mysqli_query($dblink, $query3) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
        
        // Affichage des resultats
        $data = mysqli_fetch_assoc($result);
        $data2 = mysqli_fetch_assoc($result2);
        $data3 = mysqli_fetch_assoc($result3);
        
        if ($data['existant'] == 0 && $data2['existant'] == 0 && $data3['existant'] == 0) {
            header('Location: login');
            die();
        }
        
        if ($data['existant'] == 1) {
            $nom_disp = $data['prenomEtud'];
            $nom_disp2 = $data['nomEtud'];
        } else if ($data2['existant'] == 1) {
            $nom_disp = $data2['nomEnt'];
            } else {
                $nom_disp = $data3['prenomAdmin'];
                $nom_disp2 = $data3['nomAdmin'];
            }
        
    } else {
        header('Location: login?redirect='.$redirect.'');
        die();
    }
    $logged = 1;
} else {
    $logged = 0;
}
?>