<?php
include('nontraite.php');
    $loginreq=0;
    include('all.header.php');
    if (isset($_GET[valide]) && isset($_GET[mail]) && isset($_GET[type])) {
        // Chargement des paramètres de la DB @todo SQL
        
        // Connection SQL
        $dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));
        
        if ($_GET[type]==="etud") {
            $query = "UPDATE Etudiants SET valideEtud='1' WHERE mdpEtud='$_GET[valide]' AND mailEtud='$_GET[mail]'";
        } else {
            if ($_GET[type]==="ent") {
                $query = "UPDATE Entreprises SET valideEnt='1' WHERE mdpEnt='$_GET[valide]' AND mailEnt='$_GET[mail]'";
            } else {
                header('Location: index');
                die();
            }
        }
        // Requète SQL
                
        // Exécution de la requète
        $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
        echo "<h1>Votre compte a bien été activé !</h1>";
        echo "<p>Vous pouvez maintenant vous connecter en cliquant <a href='login'>ici</a>.</p>";
    }  else {
        echo "<h1>En attente de validation</h1>";
        echo "<p>Veuillez consulter vos emails pour valider l'inscription.</p>";
    }
    include('all.footer.php');
?>