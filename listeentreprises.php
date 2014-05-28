<?php
include ('all.header.php');
?>
        <h1>Liste des entreprises</h1>
        <div id="col">
            <h3>Recherche</h3>
            <form action="listeentreprises" method="GET" id="recherche">
          		<input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php echo $_GET[champ_rech]; ?>" /> <button id="recherche" type="submit">Rechercher</button>    
            </form>
        </div>
        <div class="cleaner h20"></div>
        <h4>Résultats</h4>
<?php
$query = "SELECT * FROM entreprises WHERE (nomEnt LIKE '%".$_GET[champ_rech]."%'";
if (isset($_GET[champ_rech])) {
    $query.=" OR telEnt LIKE '%".$_GET[champ_rech]."%'";
    $query.=" OR adresseEnt LIKE '%".$_GET[champ_rech]."%')";
} else {
    $query.=")";
}
// Exécution de la requète
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Affichage des colones
$data = mysqli_fetch_assoc($result);
if ($data == NULL) {
    echo "Aucun résultat";
} else {
    echo "<table>";
    echo "<tr><th>Nom de l'entreprise</th><th>Téléphone</th><th>Adresse</th><th>Options</th></tr>";
    // Exécution de la requète pour les valeures
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Remplissage du tableau
    while($data = mysqli_fetch_assoc($result)) {
        echo '<tr onclick="document.location.href=\'infoentreprise?id='.$data[idEnt].'\'">';
        echo "<td>$data[nomEnt]</td><td>$data[telEnt]</td><td>$data[adresseEnt]</td>";
        echo '<td><a href="infoentreprise?id='.$data[idEnt].'">Plus d\'infos</a></td>';
        echo '</tr>';
    }
    // On ferme le tableau
    echo "</table>";
}
include ('all.footer.php');
?>