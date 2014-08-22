<?php
include('all.header.php');
/**** inscription entreprise ****/
if (!isset($_POST['nomEnt']) or !isset($_POST['mailEnt']) or !isset($_POST['mdpEnt']) or !isset($_POST['mdpEnt2'])
    or !isset($_POST['prenomContactEnt']) or !isset($_POST['nomContactEnt']) or !isset($_POST['telEnt'])
    or !isset($_POST['telSecEnt']) or !isset($_POST['adresseEnt']) or !isset($_POST['latEnt']) or !isset($_POST['lngEnt'])
) {
    header('Location: ./');
    die();
}
if (strlen($_POST['nomEnt']) > 100) {
    header('Location: ./');
    die();
}
if ((strlen($_POST['mailEnt']) > 100)) {
    header('Location: ./');
    die();
}
if (!(filter_var($_POST['mailEnt'], FILTER_VALIDATE_EMAIL))) {
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
    $password = $_POST['mdpEnt'];
    $_POST['mdpEnt'] = hash('sha512', $salt . $_POST['mdpEnt']);
    $_POST['mdpEnt2'] = hash('sha512', $salt . $_POST['mdpEnt2']);
    if (!($_POST['mdpEnt'] === $_POST['mdpEnt2'])) {
        header('Location: ./');
        die();
    }
}
if (strlen($_POST['nomContactEnt']) > 50) {
    header('Location: ./');
    die();
}
if (strlen($_POST['prenomContactEnt']) > 50) {
    header('Location: index');
    die();
}
if (strlen($_POST['adresseEnt']) > 255) {
    header('Location: ./');
    die();
}
if (strlen($_POST['details']) > 1000) {
    header('Location: ./');
    die();
}


if (strlen($_POST['telEnt']) != 6) {
    include('all.footer.php');
    ?>
    <script>
        alert('Le telephone ne contient pas 6 chiffres');
        history.back();
    </script>
    <?php
    die();
} else {
    if (!preg_match("/[0-9]/", $_POST['telEnt'])) {
        header('Location: index');
        die();
    }
}

if (empty($_POST['telSecEnt'])) {
    $_POST['telSecEnt'] = "NULL";
} else {
    if (strlen($_POST['telSecEnt']) != 6) {
        include('all.footer.php');
        ?>
        <script>
            alert('Le telephone secondaire ne contient pas 6 chiffres');
            history.back();
        </script>
        <?php
        die();
    } else {
        if (!preg_match("/[0-9]/", $_POST['telSecEnt'])) {
            header('Location: index');
            die();
        }
    }
}
if ($_FILES['profilpic']['error'] <= 0) {
    if (exif_imagetype($_FILES['profilpic']['tmp_name']) != false) {
        if ($_FILES['profilpic']['size'] <= $tailleMax) {
            imageToPng($_FILES['profilpic']['tmp_name'], 500, "fichiers/profile/" . md5($_POST['mailEnt']) . ".png");
        }
    }
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare('INSERT INTO entreprises (nomEnt, mailEnt, mdpEnt, nomContactEnt, prenomContactEnt, telEnt, telSecEnt, adresseEnt, latEnt, lngEnt, detailsEnt)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'))
) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$nomFormat = ucfirst(mb_strtolower($_POST['nomContactEnt'], 'UTF-8'));
$prenomFormat = ucfirst(mb_strtolower($_POST['prenomContactEnt'], 'UTF-8'));
$stmt->bind_param('sssssssssss', $_POST['nomEnt'], $_POST['mailEnt'], $_POST['mdpEnt'], $nomFormat, $prenomFormat
    , $_POST['telEnt'], $_POST['telSecEnt'], $_POST['adresseEnt'], $_POST['latEnt'], $_POST['lngEnt'], $_POST['details']);
if (!($stmt->execute())) {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <h3>Erreur, veuillez contacter l'administrateur</h3>
        </div>
    </div>
<?php
} else {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <h3>Votre compte a bien été créé. Vous serez averti par mail dès que celui-ci aura été validé.</h3>
        </div>
    </div>
<?php
    email($mail_account, $mail_pwd, 'stages@univ-nc.nc', 'Objectif stage : L\'entreprise '.$_POST['nomEnt'].' est en attente de validation',
        'Bonjour,<br><br>L\'entreprise '.$_POST['nomEnt'].' est en attente de validation, veuillez vous connecter pour valider son compte.<br><br><a href="https://stages.univ-nc.nc/admin">Lien vers le panel admin</a>',
        'stages@univ-nc.nc',
        'Plateforme Objectif stage', '0');
}
$stmt->close();
include('all.footer.php');
die();