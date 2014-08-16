<?php
include("nontraite.php");
//@todo cette page !
include ('all.header.php');
if ($_SESSION['connected']!=="admin") {
    header('Location : /');
    die();
}
if (isset($_GET['idMsg'])) {
    $query = "DELETE FROM Contact WHERE idMsg='$_GET[idMsg])'";
    // Exécution de la requète
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
}
?>
        <h1>Liste des message du formulaire de contact</h1>
        <div id="col">
            <h3>Recherche</h3>
            <form action="messagescontact" method="GET" id="recherche">
          		<input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php echo $_GET['champ_rech']; ?>" /> <button id="recherche" type="submit">Rechercher</button>
            </form>
        </div>
        <div class="cleaner h20"></div>
        <h4>Résultats</h4>
<?php
$query = "SELECT * FROM Contact WHERE (messageContact LIKE '%".$_GET['champ_rech']."%'  OR sujetContact LIKE '%".$_GET['champ_rech']."%' OR nomContact LIKE '%".$_GET['champ_rech']."%' OR mailContact LIKE '%".$_GET['champ_rech']."%') ORDER BY dateContact DESC";
// Exécution de la requète
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Affichage des colones
$data = mysqli_fetch_assoc($result);
if ($data == NULL) {
    echo "Aucun résultat";
} else {
    echo "<table>";
    echo "<tr><th>Nom</th><th>E-mail</th><th>Sujet</th><th>Date</th></tr>";
    // Exécution de la requète pour les valeures
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Remplissage du tableau
    while($data = mysqli_fetch_assoc($result)) {
        echo '<tr onclick="document.location.href=\'messagescontact?id='.$data['idMsg'].'\'">';
        echo "<td>$data[nomContact]</td><td>$data[mailContact]</td><td>$data[sujetContact]</td><td>".utf8_encode (strftime("%#d %B %Y - %Hh%M",strtotime($data['dateContact'])))."</td>";
        echo '</tr>';
    }
    // On ferme le tableau
    echo "</table>";
}

if (isset($_GET[id])) {
    echo '<div class="cleaner h20"></div>';
    echo "<h4>Détails</h4>";
    echo '<a href="messagescontact?idMsg='.$_GET[id].'"><button class="float_r" onclick="return confirm(\'Êtes-vous sur de vouloir supprimer définitivement ce message?\');">Supprimer le message</button></a>';
    $query = "SELECT * FROM Contact WHERE idMsg='$_GET[id]'";
    // Exécution de la requète
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Affichage des colones
    $data = mysqli_fetch_assoc($result);
    echo '<div class="col_13 float_l">';
    echo "<h5>Message de </h5>".$data[nomContact]." (".$data['IP'].")";
    echo '<div class="cleaner h10"></div>';
    if ($data[idEtud] != NULL) {
        echo '<a href="majinfo?idEtud='.$data[idEtud].'">Profile</a>';
    } else if ($data[idEnt] != null) {
        echo '<a href="infoentreprise?id='.$data[idEnt].'">Profile</a>';
    }
    echo '<div class="cleaner h10"></div>';
    echo "<h5>Envoyé le</h5>".utf8_encode (strftime("%#d %B %Y &agrave; %Hh%M",strtotime($data[dateContact])));
    echo '<div class="cleaner h10"></div>';
    
    echo '</div><div class="col_23 float_r">';
    echo "<h5>Sujet</h5>".$data[sujetContact];
    echo '<div class="cleaner h10"></div>';
    echo "<h5>Message</h5>".$data[messageContact];
    echo "</div>";
    
}

include ('all.footer.php');
?>