<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin") {
    realDie();
} else {
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
    if (isset($_GET['idEnt'])) {
        $stmt = $mysqli->prepare('SELECT mailEnt, nomEnt, valideEnt FROM entreprises WHERE idEnt=?');
        $stmt->bind_param('i', $_GET['idEnt']);
        $stmt->execute();
        $stmt->bind_result($mailEnt, $nomEnt, $valideEnt);
        $stmt->fetch();
        $stmt->close();

        if ($valideEnt == 0) {
            $stmt = $mysqli->prepare('UPDATE entreprises SET valideEnt=1 WHERE idEnt = ?');
            $stmt->bind_param('i', $_GET['idEnt']);
            $stmt->execute();
            $stmt->close();

            email($mail_account, $mail_pwd, $mailEnt, "Objectif stage : Votre entreprise $nomEnt a été validée",
                "Bonjour,<br><br>Votre entreprise $nomEnt a bien été validée vous pouvez à présent vous connecter.<br><br><a href=\"https://stages.univ-nc.nc/\">Lien vers la plateforme de stages</a>",
                'stages@univ-nc.nc',
                'Plateforme Objectif stage', '0');
        }
    }

    $stmt = $mysqli->prepare('SELECT idEnt, nomEnt, telEnt, adresseEnt FROM entreprises WHERE valideEnt = 0');
    $stmt->execute();
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
        $stmt->close();
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