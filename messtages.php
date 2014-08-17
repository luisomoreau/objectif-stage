<?php
include('all.header.php');
include('logincheck.php');

if ($_SESSION['connected']!='ent') {
    realDie();
}
// Chargement des paramètres de la DB
      
$idEnt = $_SESSION['id'];
$mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
if (!($stmt = $mysqli->prepare('SELECT nomStage, sujetStage, dateDebutStage, dureeStage, idStage FROM stages WHERE idEnt=?'))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('s', $idEnt);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($nomStage, $sujetStage, $dateDebutStage, $dureeStage, $idStage);
$stmt->store_result();
?>
<div class="row">
    <div class="small-12">
<?php
if ($stmt->num_rows > 0) {
    echo '<table style="width: 100%">';
    echo "<thead><tr><th>Nom du stage</th><th>Sujet</th><th>Début du stage</th><th>Durée du stage</th><th>Options</th></tr></thead><tbody>";
    // Remplissage du tableau
    while ($stmt->fetch()) {
        echo "<tr>";
        echo '<tr onclick="document.location.href=\'infostage?id='.$idStage.'\'">';
        echo "<td>$nomStage</td><td>$sujetStage</td><td>".utf8_encode(strftime("%#d %B %Y",strtotime($dateDebutStage)))."</td><td>$dureeStage jours</td>";
        echo '<td><a href="majstage?id='.$idStage.'">Modifier</a></td>';
        echo "</tr>";
    }
    // On ferme le tableau
    echo "</tbody></table>";
} else {
    ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <h3>Vous n'avez pas de stages</h3>
        </div>
    </div>
<?php
} ?>
    </div>
</div>
<?php
include ('all.footer.php');
?>