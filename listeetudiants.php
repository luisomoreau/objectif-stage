<?php
include('all.header.php');
if ($_SESSION['connected'] !== "admin" && $_SESSION['connected'] !== "ent") {
    header('Location : /');
    die();
}
?>
    <div class="row panel">
        <div class="row">
            <div class="small-12 columns">
                <h3>Recherche</h3>
            </div>
        </div>
        <form action="listeetudiants" method="GET" id="recherche">
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
                            <span class="prefix">Afficher seulement les étudiants en recherche de stage</span>
                        </div>
                        <div class="small-4 columns">
                            <div class="switch">
                                <input id="trouveStage" name="trouveStage" type="radio"
                                       value="0" <?php if (!isset($_GET['trouveStage']) || $_GET['trouveStage'] == 0) {
                                    echo "checked";
                                } ?>>
                                <label for="trouveStage" onclick="" class="text-center">Non</label>
                                <input id="trouveStage" name="trouveStage" type="radio"
                                       value="1" <?php if (isset($_GET['trouveStage']) && $_GET['trouveStage'] == 1) {
                                    echo "checked";
                                } ?>>
                                <label for="trouveStage" onclick="" class="text-center">Oui</label>
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

if (isset($_GET['trouveStage']) && $_GET['trouveStage'] == 1) {
    $trouveStage = 0;
    if (!($stmt = $mysqli->prepare('SELECT nomEtud, prenomEtud, diplome_nom, nbCandEtud, anneeEtud FROM etudiants, diplomes WHERE (nomEtud LIKE ? OR prenomEtud LIKE ? OR anneeEtud LIKE ?) AND trouveStageEtud = ? AND filiereEtud = diplome_sise'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('sssi', $search, $search, $search, $trouveStage);
} else {
    if (!($stmt = $mysqli->prepare('SELECT nomEtud, prenomEtud, diplome_nom, nbCandEtud, anneeEtud FROM etudiants, diplomes WHERE (nomEtud LIKE ? OR prenomEtud LIKE ? OR anneeEtud LIKE ?) AND filiereEtud = diplome_sise'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('sss', $search, $search, $search);
}


if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}


$stmt->bind_result($nomEtud, $prenomEtud, $licenceEtud, $nbCandEtud, $anneeEtud);
$stmt->store_result();
if ($stmt->num_rows > 0) {
    ?>
    <div class="row">
        <div class="small-12">
            <table style="width: 100%">
                <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Licence</th>
                    <th>Année</th>
                    <th>Candidatures</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo '<tr>';
                    echo '<td>' . $nomEtud . '</td><td>' . $prenomEtud . '</td><td>' . $licenceEtud . '</td>';
                    echo '<td>' . $anneeEtud . '</td><td>' . $nbCandEtud . '</td>';
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