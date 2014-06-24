<?php
include('nontraite.php');
include('all.header.php');
// Chargement des paramètres de la DB
      
$idEnt = $_SESSION[idEnt];
// Requète SQL
$query = "SELECT nomStage, sujetStage, dateDebutStage, dureeStage, idStage FROM stages WHERE idEnt='$idEnt'";
// Exécution de la requète pour les colones
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Affichage des colones
$data = mysqli_fetch_assoc($result);
?>
<h3>Mes stages</h3>
<?php
if ($data == NULL) {
    echo "Vous n'avez pas de stages.";
} else {
    echo "<table>";
    echo "<tr><th>Nom du stage</th><th>Sujet</th><th>Début du stage</th><th>Durée du stage</th><th>Options</th></tr>";
    // Exécution de la requète pour les valeures
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Remplissage du tableau
    while($data = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo '<tr onclick="document.location.href=\'infostage?id='.$data[idStage].'\'">';
        echo "<td>$data[nomStage]</td><td>$data[sujetStage]</td><td>".utf8_encode(strftime("%#d %B %Y",strtotime($data[dateDebutStage])))."</td><td>$data[dureeStage] jours</td>";
        echo '<td><a href="majstage?id='.$data[idStage].'">Modifier</a></td>';
        echo "</tr>";
    }
    // On ferme le tableau
    echo "</table>";
}

include ('all.footer.php');
?>