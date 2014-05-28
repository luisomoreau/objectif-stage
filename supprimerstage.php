<?php
include('all.header.php');

if ($_SESSION[type] !== "entreprises" && $_SESSION[type]!=="admin") {
    header('Location: /');
    die(); 
}

if ($_SESSION[type] === "admin") {
    $query = "DELETE FROM Stages WHERE idStage='$_POST[idStage]' AND idEnt='$_POST[idEnt]'";
} else {
    $query = "DELETE FROM Stages WHERE idStage='$_POST[idStage]' AND idEnt='$_SESSION[idEnt]'";
}

// Exécution de la requète
$result = mysqli_query($dblink, $query);
if (!$result) {
    echo "Erreur lors de la suppression du stage";
    include('all.footer.php');
    die();
}
 
include('all.footer.php');
?>
<script> 
        alert('Correctement supprim\351 ');
<?php 
if ($_SESSION[type] === "admin") {
    echo 'window.location = "listestages";';
} else {
    echo 'window.location = "messtages";';
}

?>
</script>