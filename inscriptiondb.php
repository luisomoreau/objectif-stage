<?php
include('all.header.php');
    /**** inscription entreprise ****/
    if (!isset($_POST['nomEnt']) or !isset($_POST['mailEnt']) or !isset($_POST['mdpEnt']) or !isset($_POST['mdpEnt2'])
        or !isset($_POST['prenomContactEnt']) or !isset($_POST['nomContactEnt']) or !isset($_POST['telEnt'])
        or !isset($_POST['telSecEnt']) or !isset($_POST['adresseEnt']) or !isset($_POST['latEnt']) or !isset($_POST['lngEnt'])) {
            //header('Location: ./');
        var_dump(get_defined_vars());
            die("wtf");
    }
    if (strlen($_POST['nomEnt'])>100) {
           header('Location: ./');
           die(); 
    }    
    if ((strlen($_POST['mailEnt'])>100)) {
            header('Location: ./');
            die(); 
    }
    if(!(filter_var($_POST['mailEnt'], FILTER_VALIDATE_EMAIL ))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Email non valide');
            history.back();
        </script>
        <?php        
        die();
    }
    if (!preg_match('/^(?=.*\d)(?=.*[a-zA-z]).{6,16}$/', $_POST['mdpEnt'])) {
        header('Location: ./');
        die();
    } else { //mdp valide et bonne taille
        $password=$mdpEnt;
        $salt="stageunc123";
        $mdpEnt=hash('sha512',$salt.$mdpEnt);
        $mdpEnt2=hash('sha512',$salt.$mdpEnt2);
        if (!($mdpEnt===$mdpEnt2)) {
            header('Location: ./');
            die();
        }
    }
    if (strlen($_POST['nomContactEnt'])>50) {
            header('Location: ./');
            die(); 
    }    
    if (strlen($_POST['prenomContactEnt'])>50) {
            header('Location: index');
            die(); 
    }    
    if (strlen($_POST['adresseEnt'])>255) {
            header('Location: ./');
            die(); 
    }    
    

    if (strlen($_POST['telEnt'])!=6) {
        include('all.footer.php');
        ?>
        <script>
            alert('Le telephone ne contient pas 6 chiffres');
            history.back();
        </script>
        <?php
        die();
    } else {
        if (!preg_match ("/[0-9]/", $_POST['telEnt'])) {
           header('Location: index');
           die(); 
        } 
    }
    
    if (empty($_POST['telSecEnt'])) {
        $_POST['telSecEnt'] = "NULL";
    } else {
        if (strlen($_POST['telSecEnt'])!=6) {
            include('all.footer.php');
            ?>
            <script>
                alert('Le telephone secondaire ne contient pas 6 chiffres');
                history.back();
            </script>
            <?php        
            die();
        } else {
            if (!preg_match ("/[0-9]/", $_POST['telSecEnt'])) {
               header('Location: index');
               die(); 
            }
        }        
    }
    if ( $_FILES['profilpic']['error'] <= 0 ) {
        if ( exif_imagetype($_FILES['profilpic']['tmp_name']) != false ) {
            if ( $_FILES['profilpic']['size'] <= 2097152 ) {
                imageToPng($_FILES['profilpic']['tmp_name'], 500, "fichiers/profile/".md5($mailEnt).".png");
            }
        }
    }
    $mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
    if (!($stmt = $mysqli->prepare('INSERT INTO entreprises (nomEnt, mailEnt, mdpEnt, nomContactEnt, prenomContactEnt, telEnt, telSecEnt, adresseEnt, latEnt, lngEnt)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?'))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('ssssssssss', $_POST['nomEnt'], $_POST['mailEnt'], $_POST['mdpEnt'], $_POST['nomContactEnt'],
                            $_POST['prenomContactEnt'], $_POST['telEnt'], $_POST['telSecEnt'],$_POST['adresseEnt'],$_POST['latEnt'],$_POST['lngEnt']);
    if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "Erreur, le mail est déjà utilisé";
    }
    $stmt->close();
//@todo mail ent
    //echo "resulat requete".$result."<br />";
        //envoie du mail
        /*$destinataire = "$mailEnt";
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
        email($destinataire, $sujet, $message, $expediteur, $nom_expediteur, $piece_jointe);*/
    //header('Location: valider');
    die();
include('all.footer.php');
?>