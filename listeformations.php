<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin" && $_SESSION['connected'] !== "ent" && $_SESSION['statut'] !== "personnel") {
    header('Location : /');
    die();
}
if (!isset($_GET['active'])) {
    $_GET['active'] = 1;
}
?>
    <div class="row panel">
        <div class="row">
            <div class="small-12 columns">
                <h3>Recherche</h3>
            </div>
        </div>
        <form action="listeformations" method="GET" id="recherche">
            <div class="row">
                <div class="large-6 column">
                    <input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100"
                           value="<?php if (isset($_GET['champ_rech'])) {
                               echo $_GET['champ_rech'];
                           } ?>"/>
                </div>
                <div class="large-6 column">
                    <div class="row collapse">
                        <div class="small-8 columns">
                            <span class="prefix">Afficher seulement les formations activées</span>
                        </div>
                        <div class="small-4 columns">
                            <div class="switch">
                                <input id="active" name="active" type="radio"
                                       value="0" <?php if (!isset($_GET['active']) || $_GET['active'] == 0) {
                                    echo "checked";
                                } ?>>
                                <label for="active" onclick="" class="text-center">Non</label>
                                <input id="active" name="active" type="radio"
                                       value="1" <?php if (isset($_GET['active']) && $_GET['active'] == 1) {
                                    echo "checked";
                                } ?>>
                                <label for="active" onclick="" class="text-center">Oui</label>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>

            <div class="row">
                <div class="large-centered large-6 columns">
                    <input class="large button expand" id="envoyer" type="submit" value="Rechercher"/>
                </div>
            </div>
        </form>
    </div>
<?php
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);

if (isset($_GET['champ_rech'])) {
    $search = "%" . $_GET['champ_rech'] . "%";
} else {
    $search = "%%";
}

if (isset($_GET['active']) && $_GET['active'] == 1) {
    $formationActive = $_GET['active'];
    if (!($stmt = $mysqli->prepare('SELECT diplome_sise, diplome_nom, diplome_active, COUNT(idEtud) FROM diplomes LEFT JOIN etudiants ON (filiereEtud = diplome_sise) WHERE (diplome_sise LIKE ? OR diplome_nom LIKE ?) AND diplome_active = ? AND diplome_sise <> 1000000 GROUP BY diplome_sise LIMIT 50'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('ssi', $search, $search, $formationActive);
} else {
    if (!($stmt = $mysqli->prepare('SELECT diplome_sise, diplome_nom, diplome_active, COUNT(idEtud) FROM diplomes LEFT JOIN etudiants ON (filiereEtud = diplome_sise) WHERE (diplome_sise LIKE ? OR diplome_nom LIKE ?) AND diplome_sise <> 1000000 GROUP BY diplome_sise LIMIT 50'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('ss', $search, $search);
}


if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}


$stmt->bind_result($diplome_sise, $diplome_nom, $diplome_active, $nbEtud);
$stmt->store_result();
if ($stmt->num_rows > 0) {
    ?>
    <div class="row">
        <div class="small-12">
            <table style="width: 100%">
                <thead>
                <tr>
                    <th>SISE</th>
                    <th>Formation</th>
                    <th>Nb d'inscrits</th>
                    <th>Activée</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $diplome_sise . '</td><td><a href="formation?sise=' . $diplome_sise . '">' . $diplome_nom . '</a></td><td>' . $nbEtud . '</td><td>' . $diplome_active . '</td>';
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
            <h3>Pas de résultat, essayez sans accents</h3>
        </div>
    </div>
<?php
}
$stmt->close();
include('all.footer.php');
?>