<?php
include('all.header.php');
include('logincheck.php');

if ($_SESSION['connected'] == 'etud' || !isset($_POST['rp']) || !isset($_POST['lien']) || !isset($_POST['descriptif']) || !isset($_POST['sise']) ) {
    realDie();
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
$stmt = $mysqli->prepare('SELECT COUNT(*) FROM diplomes WHERE diplome_sise=?');
$stmt->bind_param('s', $_POST["sise"]);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count < 1) {
    realDie();
}

if (!is_numeric($_POST['rp']) && $_POST['rp'] != '') {
    realDie();
}

if (strlen($_POST['descriptif']) < 1000) {
    $descriptif = strip_tags($_POST['descriptif']);
} else {
    realDie();
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);

if (!($stmt = $mysqli->prepare('INSERT INTO infosformation (sise, description, lien, responsablePedagogique) VALUES (?,?,?,?)
        ON DUPLICATE KEY UPDATE sise=?, description=?, lien=?, responsablePedagogique=?'))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$stmt->bind_param('sssisssi', $_POST['sise'], $descriptif, $_POST['lien'], $_POST['rp'], $_POST['sise'], $descriptif, $_POST['lien'], $_POST['rp']);
if (!($stmt->execute())) {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <p>Echec lors de la modification de la formation veuillez réessayer !</p>
        </div>
    </div>
<?php
    $stmt->close();
} else {
    $stmt->close();
    header('Location: ' . $_SERVER["HTTP_REFERER"]);
    die();
}
?>
