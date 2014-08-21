<?php
include('all.header.php');
include('logincheck.php');

if ($_SESSION['connected'] != 'ent' || !isset($_POST['nomStage']) || !isset($_POST['prenomContactStage']) || !isset($_POST['nomContactStage']) || !isset($_POST['mailContactStage'])
    || !isset($_POST['lieuStage']) || !isset($_POST['latStage']) || !isset($_POST['stageL1']) || !isset($_POST['stageL2']) || !isset($_POST['stageL3'])
    || !isset($_POST['lngStage']) || !isset($_POST['sujetStage']) || !isset($_POST['detailsStage']) || !isset($_POST['filiereStage']) || !isset($_POST['remStage'])
    || !isset($_POST['dateDebut']) || !isset($_POST['dateFin']) || !isset($_POST['dateLimite'])
) {
    realDie();
}
if (strlen($_POST['filiereStage']) < 1000) {
    $filiereStage = strip_tags($_POST['filiereStage']);
} else {
    realDie();
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
$stmt = $mysqli->prepare('SELECT COUNT(*) FROM diplomes WHERE diplome_sise=?');
$stmt->bind_param('s', $filiereStage);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count < 1) {
    realDie();
}

if (validateDate($_POST['dateDebut'])) {
    $dateDebut = $_POST['dateDebut'];
} else {
    realDie();
}

if (validateDate($_POST['dateFin'])) {
    $dateFin = $_POST['dateFin'];
} else {
    realDie();
}

if (validateDate($_POST['dateLimite'])) {
    $dateLimite = $_POST['dateLimite'];
} else {
    realDie();
}

if (!(strtotime(str_replace('/', '-', $_POST['dateFin'])) > strtotime(str_replace('/', '-', $_POST['dateDebut']))
    && strtotime(str_replace('/', '-', $_POST['dateDebut'])) >= strtotime(str_replace('/', '-', $_POST['dateLimite'])))
) {
    echo "<a href=\"javascript:history.go(-1)\">Dates incohérantes veuillez les modifier en cliquant ici</a>";
    die();
}

if ($_POST['remStage'] == 1 || $_POST['remStage'] == 0) {
    $remStage = $_POST['remStage'];
} else {
    realDie();
}

if ($_POST['stageL1'] == 1 || $_POST['stageL1'] == 0) {
    $stageL1 = $_POST['stageL1'];
} else {
    realDie();
}
if ($_POST['stageL2'] == 1 || $_POST['stageL2'] == 0) {
    $stageL2 = $_POST['stageL2'];
} else {
    realDie();
}
if ($_POST['stageL3'] == 1 || $_POST['stageL3'] == 0) {
    $stageL3 = $_POST['stageL3'];
} else {
    realDie();
}


if (strlen($_POST['detailsStage']) < 1000) {
    $detailsStage = strip_tags($_POST['detailsStage']);
} else {
    realDie();
}

if (strlen($_POST['sujetStage']) < 1000) {
    $sujetStage = strip_tags($_POST['sujetStage']);
} else {
    realDie();
}

$_POST['lngStage'] = floatval($_POST['lngStage']);
$_POST['latStage'] = floatval($_POST['latStage']);
if (is_float($_POST['lngStage'])) {
    $lngStage = $_POST['lngStage'];
} else {
    realDie();
}

if (is_float($_POST['latStage'])) {
    $latStage = $_POST['latStage'];
} else {
    realDie();
}

if (strlen($_POST['lieuStage']) < 255) {
    $lieuStage = strip_tags($_POST['lieuStage']);
} else {
    realDie();
}

if (filter_var($_POST['mailContactStage'], FILTER_VALIDATE_EMAIL)) {
    $mailContactStage = $_POST['mailContactStage'];
} else {
    realDie();
}

if (strlen($_POST['nomContactStage']) < 50) {
    $nomContactStage = strip_tags($_POST['nomContactStage']);
} else {
    realDie();
}

if (strlen($_POST['prenomContactStage']) < 50) {
    $prenomContactStage = strip_tags($_POST['prenomContactStage']);
} else {
    realDie();
}

if (strlen($_POST['nomStage']) < 200) {
    $nomStage = strip_tags($_POST['nomStage']);
} else {
    realDie();
}

$datediff = strtotime(str_replace('/', '-', $_POST['dateFin'])) - strtotime(str_replace('/', '-', $_POST['dateDebut']));
$dureeStage = floor($datediff / (60 * 60 * 24));

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare("INSERT INTO stages(idEnt, nomStage, nomContactStage, prenomContactStage, mailContactStage, lieuStage, latStage,
                                lngStage, dateDebutStage, dateFinStage, dateLimiteStage, dureeStage,
                                sujetStage, detailsStage, l1Stage, l2Stage, l3Stage, filiereStage, remuStage)
                                VALUES (?,?,?,?,?,?,?,?,STR_TO_DATE(?, '%d/%m/%Y'),STR_TO_DATE(?, '%d/%m/%Y'),STR_TO_DATE(?, '%d/%m/%Y'),?,?,?,?,?,?,?,?)"))
) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('issssssssssissiiisi', $_SESSION["id"], $nomStage, $nomContactStage, $prenomContactStage, $mailContactStage, $lieuStage, $latStage,
    $lngStage, $dateDebut, $dateFin, $dateLimite, $dureeStage,
    $sujetStage, $detailsStage, $stageL1, $stageL2, $stageL3, $filiereStage, $remStage);
if (!($stmt->execute())) {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <p>Echec lors de dépot de votre stage veuillez réessayer !</p>
        </div>
    </div>
<?php
} else {
    header('Location: ./messtages');
    email($mail_account, $mail_pwd, 'stages@univ-nc.nc', 'Objectif stage : L\'entreprise ' . $_SESSION['identifiant'] . ' a déposé un nouveau stage',
        'Bonjour,<br><br>L\'entreprise ' . $_SESSION['identifiant'] . ' a déposé un nouveau stage ayant pour sujet : ' . $sujetStage . '.<br><br><a href="https://stages.univ-nc.nc/admin">Lien vers le panel admin</a>',
        'stages@univ-nc.nc',
        'Plateforme Objectif stage', '0');
}
$stmt->close();
die();
