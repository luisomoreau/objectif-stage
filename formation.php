<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin" && $_SESSION['connected'] !== "ent" && $_SESSION['statut'] !== "personnel") {
    header('Location : /');
    die();
}
if (!isset($_GET['sise'])) {
    header('Location : /');
    die();
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare('select diplome_sise, diplome_nom, diplome_active, description, lien, userPersonnel, mailPersonnel, civilitePersonnel, nomPersonnel, prenomPersonnel, telPersonnel, count(idEtud)
                              from diplomes
                              left join infosformation on diplome_sise = sise
                              left join personnels on responsablePedagogique = idPersonnel
                              left join etudiants on filiereEtud = diplome_sise
                              where diplome_sise=?'))
) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('s', $_GET['sise']);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($formationSise, $formationNom, $formationActive, $formationDescription, $formationLien, $rpuser, $rpmail, $rpcivilite, $rpnom, $rpprenom, $rptel, $nbetud);
$stmt->fetch();
$stmt->close();
if ($formationActive == "1") {
    $formationActive = "Oui";
} else {
    $formationActive = "Non";
}
?>
    <div class="row panel">
        <div class="row">

            <div class="large-3 columns text-center">
                <img src="images/logounc.png" alt="Logo UNC"/>
            </div>
            <div class="large-7 columns">
                <h4>Nom de la formation</h4>
                <p><em><?php echo $formationNom; ?></em></p>

                <h4>Responsable de la formation</h4>
                <p><strong>Nom : <?php echo $rpcivilite." ".$rpnom." ".$rpprenom; ?></strong><br>
                <strong>Mail </strong>: <?php echo $rpmail; ?><br>
                <strong>Téléphone </strong>: <?php echo $rptel; ?></p>

                <h4>Détail de la formation</h4>
                <p><a href="<?php echo $formationLien; ?>" target="_blank">Descritif de la formation sur le site de l'université</a></p>

                <h4>Descritif des stages demandés</h4>
                <p><?php if ($formationDescription != '') echo nl2br($formationDescription); else echo "Non renseigné." ?></p>
            </div>
            <div class="large-2 columns">
                <h4>Informations</h4>
                <p><strong>Numéro SISE</strong>: <?php echo $formationSise; ?><br>
                <strong>Formation active</strong>: <?php echo $formationActive; ?><br>
                <strong>Nombre d'étudiants:</strong> <?php echo $nbetud; ?></p>
                <?php
                if ($_SESSION['connected'] == "admin" || $_SESSION['identifiant'] == $rpuser) {
                    echo '<a href="majformation?sise='.$_GET['sise'] .'"class="button">Modifier la formation</a>';
                }
                ?>
            </div>
        </div>
    </div>
<?php
include('all.footer.php');
?>