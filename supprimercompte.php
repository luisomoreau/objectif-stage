<?php
include('nontraite.php');
include('all.header.php');

if ($_SESSION[type] !== "admin" || (!isset($_GET[idEtud]) && !isset($_GET[idEnt]))) {
    header('Location: /');
    die(); 
}
if (isset($_GET[idEtud])) {
    $query = "DELETE FROM etudiants WHERE idEtud='$_GET[idEtud]'";
} else {
    $query = "DELETE FROM entreprises WHERE idEnt='$_GET[idEnt]'";
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
if (isset($_GET[idEtud])) {
    echo 'window.location = "listeetudiants";';
} else {
    echo 'window.location = "listeentreprises";';
}
?>
</script>