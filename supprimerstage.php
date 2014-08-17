<?php
include('all.header.php');
include('logincheck.php');
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if ($_SESSION['connected'] == 'admin') {
    if (!($stmt = $mysqli->prepare('DELETE FROM stages WHERE idStage=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('i', $_GET['id']);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->close();
    header('Location: ./listestages');
    die();
} else {
    if ($_SESSION['connected'] == 'ent') {
        if (!($stmt = $mysqli->prepare('DELETE FROM stages WHERE idStage=? AND idEnt=?'))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $stmt->bind_param('ii', $_GET['id'], $_SESSION['id']);
        if (!($stmt->execute())) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $stmt->close();
        header('Location: ./messtages');
        die();
    } else {
        realDie();
    }
}
include('all.footer.php');