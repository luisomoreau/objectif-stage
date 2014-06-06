<?php
include('all.header.php');
if($_SESSION['type'] == "etudiants" || ($_SESSION['type'] === "admin" && isset($_POST['mailEtud']))) {
    /** MAJ ETUDIANT **/ 
    
    if (!isset($_POST['mailPersoEtud']) or !isset($_POST['nomEtud']) or !isset($_POST['prenomEtud']) or !isset($_POST['licenceEtud']) or !isset($_POST['sexeEtud']) or !isset($_POST['naissanceJour']) or !isset($_POST['naissanceMois']) or !isset($_POST['naissanceAnnee']) or !isset($_POST['telEtud'])) {
            header('Location: /');
            die(); 
    }
    
    // On concatène la date de naissance à partir des 3 POST + verification
    if (($_POST['naissanceAnnee']>2005) || $_POST['naissanceAnnee']<1900) {
            header('Location: /');
            die(); 
    }
    if (($_POST['naissanceMois']>12) || $_POST['naissanceMois']<1) {
            header('Location: /');
            die(); 
    }
    if (($_POST['naissanceJour']>31) || $_POST['naissanceJour']<1) {
            header('Location: /');
            die(); 
    }
        
    $naissanceEtud = $_POST['naissanceAnnee'].'-'.$_POST['naissanceMois'].'-'.$_POST['naissanceJour'];
    
    // On prépare les variables à insérer dans la BDD, on remplace par NULL si vide. (Entre simples quotes sinon)
    foreach($_POST as $col=>$val) {
            //echo $col." => ".$val."<br />";
            if(empty($val)) {
                $$col = "";
            } else {
                $$col = "$val";
            }
    }   
    
    //check erreurs
    if ((strlen($mailPersoEtud)>100)) {
            header('Location: /');
            die(); 
    }
    if(!(filter_var( $mailPersoEtud, FILTER_VALIDATE_EMAIL ))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Email perso non valide');
            history.back();
        </script>
        <?php        
        die();
    }
    if ($mdpEtud!=="Defaut123") {
        if ((strlen($mdpEtud)>25) || (strlen($mdpEtud2)>25)) {
                header('Location: /');
                die(); 
        } else { //mdp bonne taille
            if (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $mdpEtud)) {
                header('Location: /');
                die(); 
            } else { //mdp valide et bonne taille
                $salt="756f13fba8e472eff61c673d3df596d9";
                $mdpEtud=md5($mdpEtud.$salt);
                $mdpEtud2=md5($mdpEtud2.$salt);
                if (!($mdpEtud===$mdpEtud2)) {
                    header('Location: /');
                    die(); 
                }
            }
    }
    }
    if (strlen($nomEtud)>50) {
           header('Location: /');
            die(); 
    }
    if (strlen($prenomEtud)>50) {
           header('Location: /');
            die(); 
    }
    if (($licenceEtud!=="L2 SPI") && ($licenceEtud!=="L3 SPI")) {
           header('Location: /');
            die(); 
    }
    if (strlen($telEtud)!=6) {
                include('all.footer.php');
                ?>
                <script>
                    alert('Le telephone ne contient pas 6 chiffres');
                    history.back();
                </script>
                <?php        
                die();
    } else {
        if (!preg_match ("/[0-9]/", $telEtud)) {
           header('Location: /');
            die(); 
        } 
    }
    
    
    if (empty($telSecEtud)) {
        $telSecEtud = "NULL";
    } else {
        if (strlen($telSecEtud)!=6) {
                include('all.footer.php');
                ?>
                <script>
                    alert('Le telephone secondaire ne contient pas 6 chiffres');
                    history.back();
                </script>
                <?php        
                die();
        } else {
            if (!preg_match ("/[0-9]/", $telSecEtud)) {
                header('Location: /');
                die(); 
            }
        }      
    }
    
    if($trouveStageEtud!=1) {
        $trouveStageEtud=0;
    }
    
    // Upload et check logo 
    if ( $_FILES['profilpic']['error'] <= 0 ) {
        if ( exif_imagetype($_FILES['profilpic']['tmp_name']) != false ) {
            if ( $_FILES['profilpic']['size'] <= 2097152 ) {
                if ($_SESSION['type'] === "admin") {
                    imageToPng($_FILES['profilpic']['tmp_name'], 250, "fichiers/profile/".md5($_POST['mailEtud']).".png");
                } else {
                    imageToPng($_FILES['profilpic']['tmp_name'], 250, "fichiers/profile/".md5($_SESSION['identifiant']).".png");
                }
            }
        }
    }
    
    //check'
    
    $mdpEtud=str_replace("'","\'",$mdpEtud);
    $nomEtud=str_replace("'","\'",$nomEtud);
    $prenomEtud=str_replace("'","\'",$prenomEtud);
    $sexeEtud=str_replace("'","\'",$sexeEtud);
    $naissanceEtud=str_replace("'","\'",$naissanceEtud);
    $mailPersoEtud=str_replace("'","\'",$mailPersoEtud);
    $licenceEtud=str_replace("'","\'",$licenceEtud);
    $telEtud=str_replace("'","\'",$telEtud);
    $telSecEtud=str_replace("'","\'",$telSecEtud);
    
    if ($mdpEtud==="Defaut123") { 
        if ($_SESSION['type'] === "admin") {
            $query = "UPDATE etudiants SET nomEtud = '$nomEtud', prenomEtud='$prenomEtud', sexeEtud='$sexeEtud', naissanceEtud='$naissanceEtud',
             mailPersoEtud='$mailPersoEtud', licenceEtud='$licenceEtud', telEtud='$telEtud', telSecEtud=$telSecEtud, trouveStageEtud='$trouveStageEtud' WHERE mailEtud='$_POST[mailEtud]'";
        } else {
            $query = "UPDATE etudiants SET nomEtud = '$nomEtud', prenomEtud='$prenomEtud', sexeEtud='$sexeEtud', naissanceEtud='$naissanceEtud',
             mailPersoEtud='$mailPersoEtud', licenceEtud='$licenceEtud', telEtud='$telEtud', telSecEtud=$telSecEtud, trouveStageEtud='$trouveStageEtud' WHERE mailEtud='$_SESSION[identifiant]'";
        }
    } else {
        if ($_SESSION['type'] === "admin") {
            $query = "UPDATE etudiants SET nomEtud = '$nomEtud', prenomEtud='$prenomEtud', sexeEtud='$sexeEtud', naissanceEtud='$naissanceEtud',
             mailPersoEtud='$mailPersoEtud', licenceEtud='$licenceEtud', telEtud='$telEtud', telSecEtud=$telSecEtud, mdpEtud='$mdpEtud', trouveStageEtud='$trouveStageEtud' WHERE mailEtud='$_POST[mailEtud]'";
        } else {
            $query = "UPDATE etudiants SET nomEtud = '$nomEtud', prenomEtud='$prenomEtud', sexeEtud='$sexeEtud', naissanceEtud='$naissanceEtud',
             mailPersoEtud='$mailPersoEtud', licenceEtud='$licenceEtud', telEtud='$telEtud', telSecEtud=$telSecEtud, mdpEtud='$mdpEtud', trouveStageEtud='$trouveStageEtud' WHERE mailEtud='$_SESSION[identifiant]'";
        }    
        if ($_SESSION['type'] != "admin") $_SESSION['mdp']=$mdpEtud;
    }
    //echo "<br />".$query."<br />";  
    // Exécution de la requète
    $result = mysqli_query($dblink, $query);
    if (!$result) {
        echo "Erreur lors de la mise à jour";
        include('all.footer.php');
        die();
    }   
    header('Location: compte');
    die();
 
} else if ($_SESSION['type'] == "entreprises" || ($_SESSION['type'] === "admin" && isset($_POST[mailEnt]))) {
    /** MAJ ENTREPRISE **/ 

    if (!isset($_POST['mdpEnt']) or !isset($_POST['mdpEnt2']) or !isset($_POST['latEnt']) or !isset($_POST['lngEnt']) or !isset($_POST['nomEnt']) or !isset($_POST['mailEnt']) or !isset($_POST['nomContactEnt']) or !isset($_POST['prenomContactEnt']) or !isset($_POST['telEnt']) or !isset($_POST['adresseEnt']) ) {
            header('Location: /');
            die();
    }
    foreach($_POST as $col=>$val) {
            if(empty($val)) {
                $$col = "";
            } else {
                $$col = "$val";
            }
    }   
    //verification erreurs
    
    if (strlen($nomEnt)>100) {
           header('Location: /');
           die(); 
    }    
    if ((strlen($mailEnt)>100)) {
            header('Location: /');
            die(); 
    }
    if(!(filter_var( $mailEnt, FILTER_VALIDATE_EMAIL ))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Email non valide');
            history.back();
        </script>
        <?php        
        die();
    } 
    if ($mdpEnt!=="Defaut123") {
        if ((strlen($mdpEnt)>25) || (strlen($mdpEnt2)>25)) {
                header('Location: /');
                die();
        } else { //mdp bonne taille
            if (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $mdpEnt)) {
                header('Location: /');
                die(); 
            } else { //mdp valide et bonne taille
                $salt="756f13fba8e472eff61c673d3df596d9";
                $mdpEnt=md5($mdpEnt.$salt);
                $mdpEnt2=md5($mdpEnt2.$salt);
                if (!($mdpEnt===$mdpEnt2)) {
                    header('Location: /');
                    die(); 
                }
            }
        }  
    }
    if (strlen($nomContactEnt)>50) {
            header('Location: /');
            die(); 
    }    
    if (strlen($prenomContactEnt)>50) {
            header('Location: /');
            die(); 
    }    
    if (strlen($adresseEnt)>255) {
            header('Location: /');
            die(); 
    } 
    if (strlen($telEnt)!=6) {
                include('all.footer.php');
                ?>
                <script>
                    alert('Le telephone ne contient pas 6 chiffres');
                    history.back();
                </script>
                <?php        
                die();
    } else {
        if (!preg_match ("/[0-9]/", $telEnt)) {
           header('Location: /');
           die(); 
        } 
    }
    if (empty($telSecEnt)) {
        $telSecEnt = "NULL";
    } else {
        if (strlen($telSecEnt)!=6) {
            include('all.footer.php');
            ?>
            <script>
                alert('Le telephone ne contient pas 6 chiffres');
                history.back();
            </script>
            <?php        
            die();
        } else {
            if (!preg_match ("/[0-9]/", $telSecEnt)) {
               header('Location: /');
               die(); 
            }
        }        
    }
    
    /* Conversion en float car POST = string */
    $latEnt = (float)$latEnt;
    $lngEnt = (float)$lngEnt;
    
    if (!is_float($latEnt)) {
       header('Location: /');
       die(); 
    }
    
    if (!is_float($lngEnt)) {
       header('Location: /');
       die(); 
    }
    
    // Upload et check logo 
    if ( $_FILES['profilpic']['error'] <= 0 ) {
        if ( exif_imagetype($_FILES['profilpic']['tmp_name']) != false ) {
            if ( $_FILES['profilpic']['size'] <= 2097152 ) {
                if ($_SESSION['type'] === "admin") {
                    imageToPng($_FILES['profilpic']['tmp_name'], 200, "fichiers/profile/".md5($_POST['mailEnt']).".png");
                } else {
                    imageToPng($_FILES['profilpic']['tmp_name'], 200, "fichiers/profile/".md5($_SESSION['identifiant']).".png");
                }
            }
        }
    }
    
    //check '
    
    $nomEnt=str_replace("'","\'",$nomEnt);
    $mailEnt=str_replace("'","\'",$mailEnt);
    $mdpEnt=str_replace("'","\'",$mdpEnt);
    $nomContactEnt=str_replace("'","\'",$nomContactEnt);
    $prenomContactEnt=str_replace("'","\'",$prenomContactEnt);
    $telEnt=str_replace("'","\'",$telEnt);
    $telSecEnt=str_replace("'","\'",$telSecEnt);
    $adresseEnt=str_replace("'","\'",$adresseEnt);
    $latEnt=str_replace("'","\'",$latEnt);
    $lngEnt=str_replace("'","\'",$lngEnt);
      
     // Chargement des paramètres de la DB
    require('sqlconf.php');  
    // Connection SQL
    $dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));  
    // Requète SQL
    if ($mdpEnt==="Defaut123") {
        if ($_SESSION['type'] === "admin") {
            $query = "UPDATE entreprises SET nomEnt = '$nomEnt', mailEnt='$mailEnt', nomContactEnt='$nomContactEnt', prenomContactEnt='$prenomContactEnt',
             telEnt='$telEnt', telSecEnt=$telSecEnt, adresseEnt='$adresseEnt', latEnt='$latEnt', lngEnt='$lngEnt' WHERE mailEnt='$_POST[mailEnt]'"; 
        } else {
            $query = "UPDATE entreprises SET nomEnt = '$nomEnt', mailEnt='$mailEnt', nomContactEnt='$nomContactEnt', prenomContactEnt='$prenomContactEnt',
             telEnt='$telEnt', telSecEnt=$telSecEnt, adresseEnt='$adresseEnt', latEnt='$latEnt', lngEnt='$lngEnt' WHERE mailEnt='$_SESSION[identifiant]'";  
        }
        
    } else {
        if ($_SESSION['type'] === "admin") {
            $query = "UPDATE entreprises SET nomEnt = '$nomEnt', mailEnt='$mailEnt', nomContactEnt='$nomContactEnt', prenomContactEnt='$prenomContactEnt',
             telEnt='$telEnt', telSecEnt=$telSecEnt, adresseEnt='$adresseEnt', latEnt='$latEnt', lngEnt='$lngEnt', mdpEnt='$mdpEnt' WHERE mailEnt='$_POST[mailEnt]'";
        } else {
            $query = "UPDATE entreprises SET nomEnt = '$nomEnt', mailEnt='$mailEnt', nomContactEnt='$nomContactEnt', prenomContactEnt='$prenomContactEnt',
             telEnt='$telEnt', telSecEnt=$telSecEnt, adresseEnt='$adresseEnt', latEnt='$latEnt', lngEnt='$lngEnt', mdpEnt='$mdpEnt' WHERE mailEnt='$_SESSION[identifiant]'";
        }
        if ($_SESSION['type'] != "admin") $_SESSION['mdp']=$mdpEnt;
    }
    
    //echo "<br />".$query."<br />";  
    // Exécution de la requète
    $result = mysqli_query($dblink, $query);
    if (!$result) {
        echo "Erreur, le mail est déjà utilisé";
        include('all.footer.php');
        die();
    }   
    if ($_SESSION['type'] != "admin") $_SESSION['identifiant']=$mailEnt;
    header('Location: compte');
    die();
    
    } else if ($_SESSION['type'] === "admin") {
        /** MAJ ADMIN **/        
        if (!isset($_POST['nomAdmin']) or !isset($_POST['prenomAdmin']) or !isset($_POST['mdpAdmin'])) {
                header('Location: /');
                die(); 
        }

        // On prépare les variables à insérer dans la BDD, on remplace par NULL si vide. (Entre simples quotes sinon)
        foreach($_POST as $col=>$val) {
                //echo $col." => ".$val."<br />";
                if(empty($val)) {
                    $$col = "";
                } else {
                    $$col = "$val";
                }
        }   

        if ($mdpAdmin!=="Defaut123") {
            if ((strlen($mdpAdmin)>25) || (strlen($mdpAdmin2)>25)) {
                    header('Location: /');
                    die(); 
            } else { //mdp bonne taille
                if (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $mdpAdmin)) {
                    header('Location: /');
                    die(); 
                } else { //mdp valide et bonne taille
                    $salt="756f13fba8e472eff61c673d3df596d9";
                    $mdpAdmin=md5($mdpAdmin.$salt);
                    $mdpAdmin2=md5($mdpAdmin2.$salt);
                    if (!($mdpAdmin===$mdpAdmin2)) {
                        header('Location: /');
                        die(); 
                    }
                }
            }
        }
        
        if (strlen($nomAdmin)>50) {
               header('Location: /');
                die(); 
        }
        if (strlen($prenomAdmin)>50) {
               header('Location: /');
                die(); 
        }
    
    
        // Upload et check logo 
        if ( $_FILES['profilpic']['error'] <= 0 ) {
            if ( exif_imagetype($_FILES['profilpic']['tmp_name']) != false ) {
                if ( $_FILES['profilpic']['size'] <= 2097152 ) {
                    imageToPng($_FILES['profilpic']['tmp_name'], 250, "fichiers/profile/".md5($_SESSION['identifiant']).".png");
                }
            }
        }
        
        $nomAdmin=str_replace("'","\'",$nomAdmin);
        $prenomAdmin=str_replace("'","\'",$prenomAdmin);
        if ($mdpAdmin==="Defaut123") { 
            $query = "UPDATE administrateurs SET nomAdmin='$nomAdmin', prenomAdmin='$prenomAdmin' WHERE mailAdmin='$_SESSION[identifiant]'";
        } else {
            $query = "UPDATE administrateurs SET nomAdmin='$nomAdmin', prenomAdmin='$prenomAdmin', mdpAdmin='mdpAdmin' WHERE mailAdmin='$_SESSION[identifiant]'";   
            $_SESSION['mdp']=$mdpAdmin;
        } 
        // Exécution de la requète
        $result = mysqli_query($dblink, $query);
        if (!$result) {
            echo "Erreur lors de la mise à jour";
            include('all.footer.php');
            die();
        }   
        header('Location: compte');
        die();
        
    } else {
        header('Location: ./');
        die();
    }
include('all.footer.php');
?>