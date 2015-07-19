<?php
include('all.header.php');
include('logincheck.php');
if (!isset($_GET['sise'])) {
    header('Location : /');
    die();
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare('select diplome_sise, diplome_nom, diplome_active, description, lien, idPersonnel, userPersonnel, mailPersonnel, civilitePersonnel, nomPersonnel, prenomPersonnel, telPersonnel, count(idEtud)
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
$stmt->bind_result($formationSise, $formationNom, $formationActive, $formationDescription, $formationLien, $rpid, $rpuser, $rpmail, $rpcivilite, $rpnom, $rpprenom, $rptel, $nbetud);
$stmt->fetch();
$stmt->close();

if ($_SESSION['connected'] !== "admin" && $_SESSION['identifiant'] !== $rpuser) {
    header('Location : /');
}

if (isset($_GET['action'])) {
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
    if ($_GET['action'] == "activer") {
        if (!($stmt = $mysqli->prepare('update diplomes set diplome_active=1 where diplome_sise=?'))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    } elseif ($_GET['action'] == "desactiver") {
        if (!($stmt = $mysqli->prepare('update diplomes set diplome_active=0 where diplome_sise=?'))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }
    $stmt->bind_param('s', $_GET['sise']);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->close();
    header('Location: ' . $_SERVER["HTTP_REFERER"]);
    die();
}

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
            <form method="post" action="majformationdb">
                <input type="hidden" name="sise" value="<?php echo $_GET['sise']; ?>">
                <div class="large-7 columns">
                    <h4>Nom de la formation</h4>
                    <p><em><?php echo $formationNom; ?></em></p>

                    <h4>Responsable de la formation</h4>
                    <p>
                        <select name="rp">
                            <option value="">Selectionner le responsable pédagogique...</option>
                            <?php
                            $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
                            if (!($stmt = $mysqli->prepare('select idPersonnel, civilitePersonnel, nomPersonnel, prenomPersonnel from personnels order by nomPersonnel asc'))) {
                                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                            }
                            if (!($stmt->execute())) {
                                echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                            }
                            $stmt->bind_result($idPersonnel, $civilitePersonnel, $nomPersonnel, $prenomPersonnel);
                            $stmt->store_result();
                            while ($stmt->fetch()) {
                                echo '<option value="'.$idPersonnel.'" ';
                                if($rpid == $idPersonnel) {
                                    echo 'selected';
                                }
                                echo '>'.$civilitePersonnel.' '.$nomPersonnel.' '.$prenomPersonnel.'</option>';
                            }
                            ?>
                        </select>
                    </p>

                    <h4>Détail de la formation (Lien vers le site de l'UNC ou un PDF)</h4>
                    <p><input type="text" name="lien" id="lien" maxlength="200" placeholder="Lien vers le descriptif de la formation sur le site de l'UNC. (http://...)" value="<?php echo $formationLien; ?>"/></p>

                    <h4>Descritif des stages demandés</h4>
                    <p>Renseigner les détails rélatifs aux stages qui devrons être proposés<br>
                    <textarea name="descriptif" id="descriptif" maxlength="1000" rows="10"><?php echo $formationDescription; ?></textarea></p>
                    <input class="large button expand" id="envoyer" type="submit" value="Mettre à jour les informations"/>
                </div>
            </form>
            <div class="large-2 columns">
                <h4>Informations</h4>
                <p><strong>Numéro SISE</strong>: <?php echo $formationSise; ?><br>
                    <strong>Formation active</strong>: <?php echo $formationActive; ?><br>
                    <strong>Nombre d'étudiants:</strong> <?php echo $nbetud; ?></p>
                <?php
                if ($formationActive == "Oui") {
                    echo '<a href="majformation?sise='.$_GET['sise'] .'&action=desactiver" class="button" onclick="return confirm(\'Êtes-vous sur de vouloir désactiver cette formation?\');">Désactiver la formation</a>';
                } else {
                    echo '<a href="majformation?sise='.$_GET['sise'] .'&action=activer" class="button" onclick="return confirm(\'Êtes-vous sur de vouloir activer cette formation?\');">Activer la formation</a>';
                }
                ?>
            </div>
        </div>
    </div>
<?php
include('all.footer.php');
?>