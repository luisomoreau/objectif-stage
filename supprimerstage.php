<?php
include('all.header.php');
include('logincheck.php');

if ($_SESSION['connected']=='admin') {
    $mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
    if (!($stmt = $mysqli->prepare('DELETE FROM stages WHERE idStage=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('i', $_GET['id']);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->close();
}