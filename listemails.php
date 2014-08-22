<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin") {
    header('Location : /');
    die();
}
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (isset($_GET['idMail'])) {
    $stmt = $mysqli->prepare('DELETE FROM mail WHERE idMail=?');
    $stmt->bind_param('s', $_GET['idMail']);
    $stmt->execute();
    $stmt->close();
}
?>
    <div class="row panel">
        <div class="large-12 columns">
            <h3>Recherche</h3>

            <form action="listemails" method="GET" id="recherche">
                <input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php if (isset($_GET['champ_rech'])) echo $_GET['champ_rech']; ?>"/>

                <div class="row">
                    <div class="large-centered large-6 columns">
                        <input class="large button expand" id="envoyer" type="submit" value="Rechercher"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
$stmt = $mysqli->prepare('SELECT idMail, destinataireMail, sujetMail, dateEnvoiMail, messageMail FROM mail as M, etudiants as Etud, entreprises as Ent
                            WHERE  M.idEnt=Ent.idEnt AND M.userEtud=Etud.userEtud AND (destinataireMail
                                LIKE ?  OR sujetMail LIKE ? OR messageMail LIKE ? OR prenomEtud LIKE ? OR nomEtud LIKE ?) ORDER BY dateEnvoiMail DESC');
if (isset($_GET['champ_rech'])) {
    $search = "%" . $_GET['champ_rech'] . "%";
} else {
    $search = "%%";
}
$stmt->bind_param('sssss', $search, $search, $search, $search, $search);
$stmt->execute();
$stmt->bind_result($idMail, $destinataireMail, $sujetMail, $dateEnvoiMail, $messageMail, $nomEnt, $prenomEnt, $nomEtud);
$stmt->store_result();
if ($stmt->num_rows > 0) {
    ?>
    <div class="row">
        <div class="small-12">
            <table style="width: 100%">
                <thead>
                <tr>
                    <th>Expediteur</th>
                    <th>Destinataire</th>
                    <th>Sujet</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while ($stmt->fetch()) {
                    echo '<tr onclick="document.location.href=\'listemails?id=' . $idMail . '\'"> ';
                    echo '<td>' . $expediteurMail . '</td><td>' . $destinataireMail . '</td><td>' . $sujetMail . '</td>';
                    echo '<td>' . strftime("%#d %B %Y à %Hh%M", strtotime($dateEnvoiMail)) . '</td>';
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
    $stmt = $mysqli->prepare('SELECT idMail, destinataireMail, sujetMail, dateEnvoiMail, messageMail, cvMail, idEtud, nomEnt, prenomEtud, nomEtud FROM mail as M, etudiants as Etud, entreprises as Ent
                            WHERE  M.idEnt=Ent.idEnt AND M.userEtud=Etud.userEtud
                            AND idMail=?');
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->bind_result($idMail, $destinataireMail, $sujetMail, $dateEnvoiMail, $messageMail, $cvMail, $idEtud, $nomEnt, $prenomEnt, $nomEtud);
    $stmt->fetch();
    ?>
    <div class="row">
        <div class="large-8 large-centered panel columns">
            <div class="row">
                <div class="large-6 columns">
                    <h4>Détails</h4>
                </div>
                <div class="large-6 columns text-right">
                    <a href="majinfo?idEtud=<?php echo $idEtud; ?>" class="button">Voir le profil</a>
                    <a href="listemails?idMail=<?php echo $_GET['id']; ?>" class="button" onclick="return confirm('Êtes-vous sur de vouloir supprimer définitivement ce message?')">Supprimer le
                        mail</a>
                </div>
            </div>
            <div class="row collapse">
                <div class="small-4 columns">
                    <span class="prefix">Mail de </span>
                </div>
                <div class="small-8 columns">
                    <input type="text" value="<?php echo $prenomEnt.' '.$nomEtud; ?>" disabled>
                </div>
            </div>
            <div class="row collapse">
                <div class="small-4 columns">
                    <span class="prefix">Envoyé le </span>
                </div>
                <div class="small-8 columns">
                    <input type="text" value="<?php echo strftime("%#d %B %Y &agrave; %Hh%M", strtotime($dateEnvoiMail)); ?>" disabled>
                </div>
            </div>
            <div class="row collapse">
                <div class="small-4 columns">
                    <span class="prefix">Objet </span>
                </div>
                <div class="small-8 columns">
                    <input type="text" value="<?php echo $sujetMail; ?>" disabled>
                </div>
            </div>
            <br>
        </div>
    </div>
<?php
    $stmt->close();
}
include('all.footer.php');