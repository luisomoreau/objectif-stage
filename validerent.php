<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin") {
    realDie();
} else {
    if (isset($_GET['idEnt'])) {
        $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
        if (!($stmt = $mysqli->prepare('UPDATE entreprises SET valideEnt=1 WHERE idEnt = ?'))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $stmt->bind_param('i', $_GET['idEnt']);
        if (!($stmt->execute())) {
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
    if (!($stmt = $mysqli->prepare('SELECT idEnt, nomEnt, telEnt, adresseEnt FROM entreprises WHERE valideEnt = 0'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
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
            <h4>Liste des entreprises non validées </h4>
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
            <div class="small-12">
                <h4>Pas d'entreprises à valider</h4>
            </div>
        </div>
<?php
    }
}
include('all.footer.php');
?>