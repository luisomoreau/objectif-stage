<?php
include('all.header.php');
include('logincheck.php');
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if ($_SESSION['connected'] == "etud" || ($_SESSION['connected'] === "admin" && isset($_POST['mailEtud']))) {
    /** MAJ ETUDIANT **/

    if (!isset($_POST['mailPersoEtud']) or !isset($_POST['telSecEtud'])) {
        header('Location: ./');
        die();
    }


    // On prépare les variables à insérer dans la BDD, on remplace par NULL si vide. (Entre simples quotes sinon)
    foreach ($_POST as $col => $val) {
        //echo $col." => ".$val."<br />";
        if (empty($val)) {
            $$col = "";
        } else {
            $$col = "$val";
        }
    }

    if (!validateDate($naissanceEtud)) {
        header('Location: ./');
        die();
    } else {
        $naissanceEtud=date("Y-m-d", strtotime(str_replace('/', '-', $naissanceEtud)));
    }

    //check erreurs
    if ((strlen($mailPersoEtud) > 100)) {
        header('Location: ./');
        die();
    }
    if (!(filter_var($mailPersoEtud, FILTER_VALIDATE_EMAIL))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Email perso non valide');
            history.back();
        </script>
        <?php
        die();
    }
    if (strlen($telSecEtud) != 6) {
        /*
                include('all.footer.php');
                ?>
                <script>
                    alert('Le telephone ne contient pas 6 chiffres');
                    history.back();
                </script>
                <?php
                die();
        */
        $telSecEtud = null;
    } else {
        if (!preg_match("/[0-9]/", $telSecEtud)) {
            header('Location: ./');
            die();
        }
    }

    if ($trouveStageEtud == "1") {
        $trouveStageEtud = 1;
    } else {
        $trouveStageEtud = 0;
    }

    // Upload et check logo 
    if ($_FILES['profilpic']['error'] <= 0) {
        if (exif_imagetype($_FILES['profilpic']['tmp_name']) != false) {
            if ($_FILES['profilpic']['size'] <= 2097152) {
                if ($_SESSION['connected'] === "admin") {
                    imageToPng($_FILES['profilpic']['tmp_name'], 500, "fichiers/profile/" . md5($_POST['mailEtud']) . ".png");
                } else {
                    imageToPng($_FILES['profilpic']['tmp_name'], 500, "fichiers/profile/" . md5($_SESSION['identifiant']) . ".png");
                }
            }
        }
    }
    //protection injection scripte à faire + date check @todo
    if ($_SESSION['connected'] === "admin") {
        $stmt = $mysqli->prepare('UPDATE etudiants SET mailPersoEtud=?, telSecEtud=?, naissanceEtud=?, trouveStageEtud=? WHERE mailEtud=?');
        $stmt->bind_param('sssis', $mailPersoEtud, $telSecEtud, $naissanceEtud, $trouveStageEtud, $_POST['mailEtud']);
    } else {
        $stmt = $mysqli->prepare('UPDATE etudiants SET mailPersoEtud=?, telSecEtud=?, naissanceEtud=?, trouveStageEtud=? WHERE userEtud=?');
        $stmt->bind_param('sssis', $mailPersoEtud, $telSecEtud, $naissanceEtud, $trouveStageEtud, $_SESSION['identifiant']);
    }

    if (!($stmt->execute())) {
        echo "Erreur lors de la mise à jour";
        include('all.footer.php');
        die();
    }
    //header('Location: compte');
    die();

} else if ($_SESSION['connected'] == "ent" || ($_SESSION['connected'] === "admin" && isset($_POST['mailEnt']))) {
    /** MAJ ENTREPRISE **/

    if (!isset($_POST['mdpEnt']) or !isset($_POST['mdpEnt2']) or !isset($_POST['latEnt']) or !isset($_POST['lngEnt']) or !isset($_POST['nomEnt']) or !isset($_POST['mailEnt']) or !isset($_POST['nomContactEnt']) or !isset($_POST['prenomContactEnt']) or !isset($_POST['telEnt']) or !isset($_POST['adresseEnt'])) {
        header('Location: ./');
        die();
    }
    foreach ($_POST as $col => $val) {
        if (empty($val)) {
            $$col = "";
        } else {
            $$col = "$val";
        }
    }
    //verification erreurs

    if (strlen($nomEnt) > 100) {
        header('Location: ./');
        die();
    }
    if ((strlen($mailEnt) > 100)) {
        header('Location: ./');
        die();
    }
    if (!(filter_var($mailEnt, FILTER_VALIDATE_EMAIL))) {
        include('all.footer.php');
        ?>
        <script>
            alert('Email non valide');
            history.back();
        </script>
        <?php
        die();
    }
    if ($mdpEnt !== "Defaut123") {
        if (!preg_match('/^(?=.*\d)(?=.*[a-zA-z]).{6,16}$/', $_POST['mdpEnt'])) {
            header('Location: ./');
            die();
        } else { //mdp valide et bonne taille
            $password = $_POST['mdpEnt'];
            $_POST['mdpEnt'] = hash('sha512', $salt . $_POST['mdpEnt']);
            $_POST['mdpEnt2'] = hash('sha512', $salt . $_POST['mdpEnt2']);
            if (!($_POST['mdpEnt'] === $_POST['mdpEnt2'])) {
                header('Location: ./');
                die();
            }
        }
    }
    if (strlen($nomContactEnt) > 50) {
        header('Location: ./');
        die();
    }
    if (strlen($prenomContactEnt) > 50) {
        header('Location: ./');
        die();
    }
    if (strlen($adresseEnt) > 255) {
        header('Location: ./');
        die();
    }
    if (strlen($telEnt) != 6) {
        include('all.footer.php');
        ?>
        <script>
            alert('Le telephone ne contient pas 6 chiffres');
            history.back();
        </script>
        <?php
        die();
    } else {
        if (!preg_match("/[0-9]/", $telEnt)) {
            header('Location: ./');
            die();
        }
    }
    if (empty($telSecEnt)) {
        $telSecEnt = "NULL";
    } else {
        if (strlen($telSecEnt) != 6) {
            include('all.footer.php');
            ?>
            <script>
                alert('Le telephone ne contient pas 6 chiffres');
                history.back();
            </script>
            <?php
            die();
        } else {
            if (!preg_match("/[0-9]/", $telSecEnt)) {
                header('Location: ./');
                die();
            }
        }
    }

    /* Conversion en float car POST = string */
    $latEnt = (float)$latEnt;
    $lngEnt = (float)$lngEnt;

    if (!is_float($latEnt)) {
        header('Location: ./');
        die();
    }

    if (!is_float($lngEnt)) {
        header('Location: ./');
        die();
    }

    // Upload et check logo 
    if ($_FILES['profilpic']['error'] <= 0) {
        if (exif_imagetype($_FILES['profilpic']['tmp_name']) != false) {
            if ($_FILES['profilpic']['size'] <= 2097152) {
                imageToPng($_FILES['profilpic']['tmp_name'], 500, "fichiers/profile/" . md5($_POST['mailEnt']) . ".png");
            }
        }
    }

    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);


    if ($mdpEnt === "Defaut123") {
        if ($_SESSION['connected'] === "admin") {
            $stmt = $mysqli->prepare('UPDATE entreprises
                                        SET nomEnt = ?,mailEnt=?, nomContactEnt=?, prenomContactEnt=?,
                                            telEnt=?, telSecEnt=?, adresseEnt=?, latEnt=?, lngEnt=?
                                        WHERE mailEnt=?');
            $stmt->bind_param('ssssssssss', $nomEnt, $mailEnt, $nomContactEnt, $prenomContactEnt, $telEnt, $telSecEnt, $adresseEnt, $latEnt, $lngEnt, $_POST['mailEnt']);
        } else {
            $stmt = $mysqli->prepare('UPDATE entreprises
                                        SET nomEnt = ?,mailEnt=?, nomContactEnt=?, prenomContactEnt=?,
                                            telEnt=?, telSecEnt=?, adresseEnt=?, latEnt=?, lngEnt=?
                                        WHERE idEnt=?');
            $stmt->bind_param('sssssssssi', $nomEnt, $mailEnt, $nomContactEnt, $prenomContactEnt, $telEnt, $telSecEnt, $adresseEnt, $latEnt, $lngEnt, $_SESSION['idEnt']);
        }
    } else { //@todo admin
        if ($_SESSION['connected'] === "admin") {
            $query = "UPDATE entreprises SET nomEnt = '$nomEnt', mailEnt='$mailEnt', nomContactEnt='$nomContactEnt', prenomContactEnt='$prenomContactEnt',
             telEnt='$telEnt', telSecEnt=$telSecEnt, adresseEnt='$adresseEnt', latEnt='$latEnt', lngEnt='$lngEnt', mdpEnt='$mdpEnt' WHERE mailEnt='$_POST[mailEnt]'";
        } else {
            $query = "UPDATE entreprises SET nomEnt = '$nomEnt', mailEnt='$mailEnt', nomContactEnt='$nomContactEnt', prenomContactEnt='$prenomContactEnt',
             telEnt='$telEnt', telSecEnt=$telSecEnt, adresseEnt='$adresseEnt', latEnt='$latEnt', lngEnt='$lngEnt', mdpEnt='$mdpEnt' WHERE mailEnt='$_SESSION[identifiant]'";
        }
        if ($_SESSION['connected'] != "admin") $_SESSION['mdp'] = $mdpEnt;
    }
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->close();
    header('Location: ./compte');
    die();

} else if ($_SESSION['connected'] === "admin") {
    /** MAJ ADMIN **/
    if (!isset($_POST['nomAdmin']) or !isset($_POST['prenomAdmin']) or !isset($_POST['mdpAdmin'])) {
        header('Location: ./');
        die();
    }

    // On prépare les variables à insérer dans la BDD, on remplace par NULL si vide. (Entre simples quotes sinon)
    foreach ($_POST as $col => $val) {
        //echo $col." => ".$val."<br />";
        if (empty($val)) {
            $$col = "";
        } else {
            $$col = "$val";
        }
    }

    if ($mdpAdmin !== "Defaut123") {
        if ((strlen($mdpAdmin) > 25) || (strlen($mdpAdmin2) > 25)) {
            header('Location: ./');
            die();
        } else { //mdp bonne taille
            if (!preg_match('/[A-Z]+[a-z]+[0-9]+/', $mdpAdmin)) {
                header('Location: ./');
                die();
            } else { //mdp valide et bonne taille
                $salt = "756f13fba8e472eff61c673d3df596d9";
                $mdpAdmin = md5($mdpAdmin . $salt);
                $mdpAdmin2 = md5($mdpAdmin2 . $salt);
                if (!($mdpAdmin === $mdpAdmin2)) {
                    header('Location: ./');
                    die();
                }
            }
        }
    }

    if (strlen($nomAdmin) > 50) {
        header('Location: ./');
        die();
    }
    if (strlen($prenomAdmin) > 50) {
        header('Location: ./');
        die();
    }


    // Upload et check logo
    if ($_FILES['profilpic']['error'] <= 0) {
        if (exif_imagetype($_FILES['profilpic']['tmp_name']) != false) {
            if ($_FILES['profilpic']['size'] <= 2097152) {
                imageToPng($_FILES['profilpic']['tmp_name'], 250, "fichiers/profile/" . md5($_SESSION['identifiant']) . ".png");
            }
        }
    }

    $nomAdmin = str_replace("'", "\'", $nomAdmin);
    $prenomAdmin = str_replace("'", "\'", $prenomAdmin);
    if ($mdpAdmin === "Defaut123") {
        $query = "UPDATE administrateurs SET nomAdmin='$nomAdmin', prenomAdmin='$prenomAdmin' WHERE mailAdmin='$_SESSION[identifiant]'";
    } else {
        $query = "UPDATE administrateurs SET nomAdmin='$nomAdmin', prenomAdmin='$prenomAdmin', mdpAdmin='mdpAdmin' WHERE mailAdmin='$_SESSION[identifiant]'";
        $_SESSION['mdp'] = $mdpAdmin;
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