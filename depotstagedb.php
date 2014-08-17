<?php
include('all.header.php');
include('logincheck.php');

print_r($_POST);

if (strlen($_POST['filiereStage'])<1000) {
    $filiereStage=strip_tags($_POST['filiereStage']);
} else {
    realDie();
}

$mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
$stmt = $mysqli->prepare('SELECT COUNT(*) FROM diplomes WHERE diplome_sise=?');
$stmt->bind_param('s', $filiereStage);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count<1) {
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

if (!(strtotime(str_replace('/', '-', $_POST['dateFin']))>strtotime(str_replace('/', '-', $_POST['dateDebut']))
    && strtotime(str_replace('/', '-', $_POST['dateDebut']))>=strtotime(str_replace('/', '-', $_POST['dateLimite'])))) {
    echo "<a href=\"javascript:history.go(-1)\">Dates incohérantes veuillez les modifier en cliquant ici</a>";
    die();
}

if ($_POST['remStage'] == 1 || $_POST['remStage'] == 0) {
    $remStage=$_POST['remStage'];
} else {
    realDie();
}

if ($_POST['stageL1'] == 1 || $_POST['stageL1'] == 0) {
    $stageL1=$_POST['stageL1'];
} else {
    realDie();
}
if ($_POST['stageL2'] == 1 || $_POST['stageL2'] == 0) {
    $stageL2=$_POST['stageL2'];
} else {
    realDie();
}
if ($_POST['stageL3'] == 1 || $_POST['stageL3'] == 0) {
    $stageL3=$_POST['stageL3'];
} else {
    realDie();
}


if (strlen($_POST['detailsStage'])<1000) {
    $detailsStage=strip_tags($_POST['detailsStage']);
} else {
    realDie();
}

if (strlen($_POST['sujetStage'])<1000) {
    $sujetStage=strip_tags($_POST['sujetStage']);
} else {
    realDie();
}

$_POST['lngStage']=floatval($_POST['lngStage']);
$_POST['latStage']=floatval($_POST['latStage']);
if (is_float($_POST['lngStage'])) {
    $lngStage=$_POST['lngStage'];
} else {
    realDie();
}

if (is_float($_POST['latStage'])) {
    $latStage=$_POST['latStage'];
} else {
    realDie();
}

if (strlen($_POST['lieuStage'])<255) {
    $lieuStage=strip_tags($_POST['lieuStage']);
} else {
    realDie();
}

if (filter_var($_POST['mailContactStage'], FILTER_VALIDATE_EMAIL)) {
    $mailContactStage=$_POST['mailContactStage'];
} else {
    realDie();
}

if (strlen($_POST['nomContactStage'])<50) {
    $nomContactStage=strip_tags($_POST['nomContactStage']);
} else {
    realDie();
}

if (strlen($_POST['prenomContactStage'])<50) {
    $prenomContactStage=strip_tags($_POST['prenomContactStage']);
} else {
    realDie();
}

if (strlen($_POST['nomStage'])<200) {
    $nomStage=strip_tags($_POST['nomStage']);
} else {
    realDie();
}

$datediff = strtotime(str_replace('/', '-', $_POST['dateFin'])) - strtotime(str_replace('/', '-', $_POST['dateDebut']));
$dureeStage= floor($datediff/(60*60*24));

$mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
if (!($stmt = $mysqli->prepare('INSERT INTO stages(idEnt, nomStage, nomContactStage, prenomContactStage, mailContactStage, lieuStage, latStage,
                                lngStage, dateDebutStage, dateFinStage, dateLimiteStage, dureeStage,
                                sujetStage, detailsStage, l1Stage, l2Stage, l3Stage, filiereStage, remuStage)
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('iissssssssssisssiiiss', $_SESSION["id"], $idEnt, $nomStage, $nomContactStage, $prenomContactStage, $mailContactStage, $lieuStage, $latStage,
    $lngStage, $dateDebutStage, $dateFinStage, $dateLimiteStage, $dureeStage,
    $sujetStage, $detailsStage, $l1Stage, $l2Stage, $l3Stage, $filiereStage, $remuStage);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($test);
while ($stmt->fetch()) {
    echo $test;
}
$stmt->close();
