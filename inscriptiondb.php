<?php
$loginreq=0;
include('all.header.php');
if($_POST[typeinscription] == "etud") {
    if (!isset($_POST[mailEtud]) or !isset($_POST[mailPersoEtud]) or !isset($_POST[mdpEtud]) or !isset($_POST[mdpEtud2]) or !isset($_POST[nomEtud]) or !isset($_POST[prenomEtud]) or !isset($_POST[licenceEtud]) or !isset($_POST[sexeEtud]) or !isset($_POST[naissanceJour]) or !isset($_POST[naissanceMois]) or !isset($_POST[naissanceAnnee]) or !isset($_POST[telEtud])) {
            header('Location: index');
            die(); 
    }
    /**** inscription étudiant ****/    
    // On concatène la date de naissance à partir des 3 POST + verification
    if (($_POST[naissanceAnnee]==="Année") || ($_POST[naissanceMois]==="Mois") || ($_POST[naissanceJour]==="Jour")) { // à approfondir
        include('all.footer.php');
        ?>
        <script>
            alert('Donnez votre date de naissance');
            history.back();
        </script>
        <?php        
        die();
    } else {
        if (($_POST[naissanceAnnee]>2005) || $_POST[naissanceAnnee]<1900) {
            header('Location: index');
            die(); 
        }
        if (($_POST[naissanceMois]>12) || $_POST[naissanceMois]<1) {
            header('Location: index');
            die(); 
        }
        if (($_POST[naissanceJour]>31) || $_POST[naissanceJour]<1) {
            header('Location: index');
            die(); 
        }
    }
    $naissanceEtud = "$_POST[naissanceAnnee]-$_POST[naissanceMois]-$_POST[naissanceJour]";
    //echo "Date de naissance concat: $naissanceEtud<br /><br />";
    
    // On prépare les variables à insérer dans la BDD, on remplace par NULL si vide. (Entre simples quotes sinon)
    $_POST[mailEtud].="@etudiant.univ-nc.nc";
    foreach($_POST as $col=>$val) {
            //echo $col." => ".$val."<br />";
            if(empty($val)) {
                $$col = "";
            } else {
                $$col = "$val";
            }
    }   
    
    //check erreurs
    if ((strlen($mailEtud)>100)) {
            header('Location: index');
            die(); 
    }
    if(!(filter_var( $mailEtud, FILTER_VALIDATE_EMAIL ))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Email non valide');
            history.back();
        </script>
        <?php        
        die();
    }
    if ((strlen($mailPersoEtud)>100)) {
            header('Location: index');
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
    if ((strlen($mdpEtud)>25) || (strlen($mdpEtud2)>25)) {
            header('Location: index');
            die(); 
    } else { //mdp bonne taille
        if (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $mdpEtud)) {
            header('Location: index');
            die(); 
        } else { //mdp valide et bonne taille
            $salt="756f13fba8e472eff61c673d3df596d9";
            $password=$mdpEtud;
            $mdpEtud=md5($mdpEtud.$salt);
            $mdpEtud2=md5($mdpEtud2.$salt);            
            if (!($mdpEtud===$mdpEtud2)) {
                header('Location: index');
                die(); 
            }
        }
    }
    if (strlen($nomEtud)>50) {
           header('Location: index');
            die(); 
    }
    if (strlen($prenomEtud)>50) {
           header('Location: index');
            die(); 
    }
    if (($licenceEtud!=="L2 SPI") && ($licenceEtud!=="L3 SPI")) {
           header('Location: index');
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
           header('Location: index');
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
                header('Location: index');
                die(); 
            }
        }      
    }
        
    // Upload et check logo 
    if ( $_FILES[profilpic]['error'] <= 0 ) {
        if ( exif_imagetype($_FILES[profilpic]['tmp_name']) != false ) {           
            if ( $_FILES[profilpic]['size'] <= 2097152 ) {
                imageToPng($_FILES[profilpic]['tmp_name'], 250, "fichiers/profile/".md5($mailEtud).".png");
            }
        }
    }
    
    $mailEtud=str_replace("'","\'",$mailEtud);
    $mdpEtud=str_replace("'","\'",$mdpEtud);
    $nomEtud=str_replace("'","\'",$nomEtud);
    $prenomEtud=str_replace("'","\'",$prenomEtud);
    $sexeEtud=str_replace("'","\'",$sexeEtud);
    $naissanceEtud=str_replace("'","\'",$naissanceEtud);
    $mailPersoEtud=str_replace("'","\'",$mailPersoEtud);
    $licenceEtud=str_replace("'","\'",$licenceEtud);
    $telEtud=str_replace("'","\'",$telEtud);
    $telSecEtud=str_replace("'","\'",$telSecEtud);
      
    // Requète SQL
    $query = "INSERT INTO etudiants (mailEtud, mdpEtud, nomEtud, prenomEtud, sexeEtud, naissanceEtud, mailPersoEtud, licenceEtud, telEtud, telSecEtud)
                        VALUES ('$mailEtud', '$mdpEtud', '$nomEtud', '$prenomEtud', '$sexeEtud', '$naissanceEtud', '$mailPersoEtud', '$licenceEtud', '$telEtud', $telSecEtud)"; 
    //echo "<br />".$query."<br />";
    // Exécution de la requète
    $result = mysqli_query($dblink, $query); 
    if (!$result) {
        echo "Erreur, le mail est déjà utilisé ou la date de naissance est incorrect";
        include('all.footer.php');
        die();
    }
    
        //envoie du mail
        $destinataire = "$mailEtud";
        $sujet = "Confirmation de votre compte Objectif Stage";
        $message = stripslashes("Bonjour ".$prenomEtud." ".$nomEtud.",<br />
        <br />Vous venez de vous inscrire sur http://stages.gklt.me,et nous vous en remercions. Vous aurez bientôt accès à notre plateforme unique de stages.<br />
        <br />Dans un premier temps confirmez votre inscription en cliquant sur : <a href='http://stages.gklt.me/valider?type=etud&valide=".$mdpEtud."&mail=".$mailEtud."'>Valider votre compte</a>
        <br />Si le lien ne fonctionne pas correctement copier coller l'adresse suivante dans votre navigateur : <br />http://stages.gklt.me/valider?type=etud&valide=".$mdpEtud."&mail=".$mailEtud."
        <br /><br />Une fois votre inscription confirmée vous pourrez vous connecter.<br /><br />
        Votre login est : ".$mailEtud."<br />
        Votre mot de passe est : ".$password."
        <br /><br /><br />A tout de suite,<br />
        Cordialement,
        <br />L'équipe Objectif Stage");
        $expediteur = "noreply@stageunc.com";
        $nom_expediteur = "Objectif Stage";
        $piece_jointe = 0;
        email($destinataire, $sujet, $message, $expediteur, $nom_expediteur, $piece_jointe);
        
        
        
    header('Location: valider');
    die(); 
 
} else if($_POST[typeinscription] == "ent") {
    /**** inscription entreprise ****/
    if (!isset($_POST[latEnt]) or!isset($_POST[lngEnt]) or!isset($_POST[nomEnt]) or !isset($_POST[mailEnt]) or !isset($_POST[mdpEnt]) or !isset($_POST[mdpEnt2]) or !isset($_POST[nomContactEnt]) or !isset($_POST[prenomContactEnt]) or !isset($_POST[telEnt]) or !isset($_POST[adresseEnt]) ) {
            header('Location: index');
            die();
    }
    foreach($_POST as $col=>$val) {
            //echo $col." => ".$val."<br />";
            if(empty($val)) {
                $$col = "";
            } else {
                $$col = "$val";
            }
    }   
    //verification erreurs
    
    if (strlen($nomEnt)>100) {
           header('Location: index');
           die(); 
    }    
    if ((strlen($mailEnt)>100)) {
            header('Location: index');
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
    if ((strlen($mdpEnt)>25) || (strlen($mdpEnt2)>25)) {
            header('Location: index');
            die();
    } else { //mdp bonne taille
        if (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $mdpEnt)) {
            header('Location: index');
            die(); 
        } else { //mdp valide et bonne taille
            $salt="756f13fba8e472eff61c673d3df596d9";
            $password=$mdpEnt;
            $mdpEnt=md5($mdpEnt.$salt);
            $mdpEnt2=md5($mdpEnt2.$salt);
            if (!($mdpEnt===$mdpEnt2)) {
                header('Location: index');
                die(); 
            }
        }
    }    
    if (strlen($nomContactEnt)>50) {
            header('Location: index');
            die(); 
    }    
    if (strlen($prenomContactEnt)>50) {
            header('Location: index');
            die(); 
    }    
    if (strlen($adresseEnt)>255) {
            header('Location: index');
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
           header('Location: index');
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
               header('Location: index');
               die(); 
            }
        }        
    }   
    

    
    // Upload et check logo 
    if ( $_FILES[profilpic]['error'] <= 0 ) {
        if ( exif_imagetype($_FILES[profilpic]['tmp_name']) != false ) {
            if ( $_FILES[profilpic]['size'] <= 2097152 ) {       
                imageToPng($_FILES[profilpic]['tmp_name'], 200, "fichiers/profile/".md5($mailEnt).".png");
            }
        }
    }

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
    
    // Requète SQL
    $query = "INSERT INTO entreprises (nomEnt, mailEnt, mdpEnt, nomContactEnt, prenomContactEnt, telEnt, telSecEnt, adresseEnt, latEnt, lngEnt)
                        VALUES ('$nomEnt', '$mailEnt', '$mdpEnt', '$nomContactEnt', '$prenomContactEnt', '$telEnt', $telSecEnt, '$adresseEnt', '$latEnt', '$lngEnt')"; 
    //echo "<br />".$query."<br />";  
    // Exécution de la requète
    $result = mysqli_query($dblink, $query);
    if (!$result) {
        echo "Erreur, le mail est déjà utilisé";
        include('all.footer.php');
        die();
    }
    //echo "resulat requete".$result."<br />";
        //envoie du mail
        $destinataire = "$mailEnt";
        $sujet = "Confirmation de votre compte Objectif Stage";
        $message = stripslashes("Bonjour ".$prenomContactEnt." ".$nomContactEnt.",<br />
        <br />Vous venez de vous inscrire sur http://stages.gklt.me,et nous vous en remercions. Vous aurez bientôt accès à notre plateforme unique de stages.<br />
        <br />Dans un premier temps confirmez votre inscription en cliquant sur : <a href='http://stages.gklt.me/valider?type=ent&valide=".$mdpEnt."&mail=".$mailEnt."'>Valider votre compte</a>
        <br />Si le lien ne fonctionne pas correctement copier coller l'adresse suivante dans votre navigateur : http://stages.gklt.me/valider?type=ent&valide=".$mdpEnt."&mail=".$mailEnt."
        <br /><br />Une fois votre inscription confirmée vous pourrez vous connecter.<br /><br />
        Votre login est : ".$mailEnt."<br />
        Votre mot de passe est : ".$password."
        <br /><br /><br />A tout de suite,<br />
        Cordialement,
        <br />L'équipe Objectif Stage");
        $expediteur = "noreply@stageunc.com";
        $nom_expediteur = "Objectif Stage";
        $piece_jointe = 0;
        email($destinataire, $sujet, $message, $expediteur, $nom_expediteur, $piece_jointe);
    header('Location: valider');
    die();       
} else {
    header('Location: index');
    die();
}
include('all.footer.php');
?>