<?php
include('all.header.php');
include('logincheck.php');
?>
    <div class="row panel">
        <div class="large-12 columns">
            <h3>Recherche</h3>

            <form action="listeentreprises" method="GET" id="recherche">
                <input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php if (isset($_GET['champ_rech'])) {
                    echo $_GET['champ_rech'];
                } ?>"/>
                <div class="row">
                    <div class="large-centered large-6 columns">
                        <input class="large button expand" id="envoyer" type="submit" value="Rechercher"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);

if (isset($_GET['champ_rech'])) {
    $query = "SELECT * FROM entreprises WHERE (nomEnt LIKE '%" . $_GET['champ_rech'] . "%'";
    if (!($stmt = $mysqli->prepare('SELECT idEnt, nomEnt, telEnt, adresseEnt FROM entreprises WHERE valideEnt=1 AND (nomEnt LIKE ? OR telEnt LIKE ? OR adresseEnt LIKE ? OR detailsEnt=?)'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $search = '%' . $_GET['champ_rech'] . '%';
    $stmt->bind_param('ssss', $search, $search, $search, $search);
} else {
    if (!($stmt = $mysqli->prepare('SELECT idEnt, nomEnt, telEnt, adresseEnt FROM entreprises WHERE valideEnt=1'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
}
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($idEnt, $nomEnt, $telEnt, $adresseEnt);
$stmt->store_result();
if ($stmt->num_rows > 0) {
    ?>
    <div class="row">
        <div class="small-12">
            <table style="width: 100%">
                <thead>
                <tr>
                    <th>Nom de l'entreprise</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo '<tr onclick="document.location.href=\'infoentreprise?id=' . $idEnt . '\'">';
                    echo '<td>' . $nomEnt . '</td><td>' . $telEnt . '</td><td>' . $adresseEnt . '</td>';
                    echo '<td><a href="infoentreprise?id=' . $idEnt . '">Plus d\'infos</a></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
} else {
    ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <h3>Pas de résultat</h3>
        </div>
    </div>
<?php
}
$stmt->close();
include('all.footer.php');
?>