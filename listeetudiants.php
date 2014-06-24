<?php
include('nontraite.php');
include ('all.header.php');
if ($_SESSION[type] != "admin") {
    header('Location : /');
    die();
}
?>
        <h1>Liste des étudiants</h1>
        <div id="col">
            <h3>Recherche</h3>
            <form action="listeetudiants" method="GET" id="recherche">
          		<input placeholder="Nom, prénom" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php echo $_GET[champ_rech]; ?>" />&nbsp;&nbsp;
                <input type="checkbox" name="trouveStage" id="trouveStage" value="1" <?php if(isset($_GET[trouveStage])) { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="trouveStage" >A trouvé un stage</label>&nbsp;&nbsp;
                <input type="checkbox" name="valid" id="valid" value="1" <?php if($_GET[valid]!=0) { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="valid" >Compte valide</label>
                <div class="cleaner h10"></div>
                <div class="centrer">
                    <button type="submit">Rechercher</button>  
                </div>  
            </form>
        </div>
        <div class="cleaner h20"></div>
        <h4>Résultats</h4>
<?php
$query = "SELECT * FROM etudiants WHERE (nomEtud LIKE '%".$_GET[champ_rech]."%' OR prenomEtud LIKE '%".$_GET[champ_rech]."%')";
if (isset($_GET[trouveStage])) {
    $query.= " AND trouveStageEtud = 1";
}
// Exécution de la requète
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Affichage des colones
$data = mysqli_fetch_assoc($result);
if ($data == NULL) {
    echo "Aucun résultat";
} else {
    echo "<table>";
    echo "<tr><th>Nom</th><th>Prénom</th><th>Licence</th><th>Candidatures</th></tr>";
    // Exécution de la requète pour les valeures
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Remplissage du tableau
    while($data = mysqli_fetch_assoc($result)) {
        echo '<tr onclick="document.location.href=\'majinfo?idEtud='.$data[idEtud].'\'">';
        echo "<td>$data[nomEtud]</td><td>$data[prenomEtud]</td><td>$data[licenceEtud]</td><td>$data[nbCandEtud]</td>";
        echo '</tr>';
    }
    // On ferme le tableau
    echo "</table>";
}
include ('all.footer.php');
?>