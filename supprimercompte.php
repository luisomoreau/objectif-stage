<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin" || (!isset($_GET['idEtud']) && !isset($_GET['idEnt']))) {
    header('Location: ./');
    die();
}
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (isset($_GET['idEtud'])) {
    if (!($stmt = $mysqli->prepare('DELETE FROM etudiants WHERE idEtud=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('i', $_GET['idEtud']);
} else {
    if (!($stmt = $mysqli->prepare('DELETE FROM entreprises WHERE idEnt=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('i', $_GET['idEnt']);
}
if (!($stmt->execute())) {
    echo "Erreur lors de la suppression du stage";
    include('all.footer.php');
    die();
}
$stmt->close();
include('all.footer.php');
?>
<script>
    alert('Correctement supprim\351 ');
    <?php
    if (isset($_GET['idEtud'])) {
        echo 'window.location = "listeetudiants";';
    } else {
        echo 'window.location = "listeentreprises";';
    }
    ?>
</script>