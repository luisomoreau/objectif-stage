<?php
include('all.header.php');
if ($_SESSION['connected'] !== "admin") {
    header('Location : /');
    die();
}
if (isset($_GET['idMail'])) {
    $query = "DELETE FROM Mail WHERE idMail='$_GET[idMail])'";
    // Exécution de la requète
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: " . mysqli_error($dblink));
}
?>
    <h1>Liste des E-mails envoyés via la plateforme</h1>
    <div id="col">
        <h3>Recherche</h3>

        <form action="listemails" method="GET" id="recherche">
            <input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php if (isset($_GET['champ_rech'])) echo $_GET['champ_rech']; ?>"/>
            <button id="recherche" type="submit">Rechercher</button>
        </form>
    </div>
    <div class="cleaner h20"></div>
    <h4>Résultats</h4>
<?php
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare('SELECT idMail, destinataireMail, expediteurMail, sujetMail, dateEnvoiMail, messageMail FROM Mail WHERE destinataireMail
                                LIKE ?  OR sujetMail LIKE ? OR messageMail LIKE ? OR expediteurMail LIKE ? ORDER BY dateEnvoiMail DESC'))
) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (isset($_GET['champ_rech'])) {
    $search = "%" . $_GET['champ_rech'] . "%";
} else {
    $search = "%%";
}
$stmt->bind_param('ssss', $search, $search, $search, $search);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($idMail, $destinataireMail, $expediteurMail, $sujetMail, $dateEnvoiMail, $messageMail);
$stmt->store_result();
if ($stmt->num_rows > 0) {
    ?>
    <div class="row">
        <div class="small-12">
            <table style="width: 100%">
                <thead>
                <tr>
                    <th>Destinataire</th>
                    <th>Expediteur</th>
                    <th>Sujet</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo '<tr onclick="document.location.href=\'listemails?id='.$idMail.'\'"> ';
                    echo '<td>' . $destinataireMail . '</td><td>' . $expediteurMail . '</td><td>' . $sujetMail . '</td>';
                    echo '<td>' . strftime("%#d %B %Y à %Hh%M",strtotime($dateEnvoiMail)) . '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
    $stmt->close();
} else {
    ?>
    <div class="row">
        <div class="small-12 columns text-center">
            <h3>Pas de résultat</h3>
        </div>
    </div>
<?php
}
if (isset($_GET['id'])) {

    echo "<h4>Détails</h4>";
    echo '<a href="listemails?idMail='.$_GET['id'].'"><button class="float_r" onclick="return confirm(\'Êtes-vous sur de vouloir supprimer définitivement ce message?\');">Supprimer le message</button></a>';
    $query = "SELECT * FROM Mail WHERE idMail='$_GET[id]'";
    // Exécution de la requète
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Affichage des colones
    $data = mysqli_fetch_assoc($result);
    echo '<div class="col_13 float_l">';
    echo "<h5>Mail de </h5>".$data['expediteurMail'];
    echo '<div class="cleaner h10"></div>';
    echo '<a href="majinfo?idEtud='.$data['idEtud'].'">Profile</a>';
    echo '<div class="cleaner h10"></div>';
    echo "<h5>Envoyé le</h5>".utf8_encode (strftime("%#d %B %Y &agrave; %Hh%M",strtotime($data['dateEnvoiMail'])));
    echo '<div class="cleaner h10"></div>';
    echo "<h5>Pièce jointe:</h5>";
    if ($data[cvMail] == "0") {
        echo "aucune";
    } else {
        echo '<a href="'.$data['cvMail'].'" target="_blank">CV</a>';
    }
    echo '</div><div class="col_23 float_r">';
    echo "<h5>Sujet</h5>".$data['sujetMail'];
    echo '<div class="cleaner h10"></div>';
    echo "<h5>Message</h5>".$data['messageMail'];
    echo "</div>";
}

include('all.footer.php');
