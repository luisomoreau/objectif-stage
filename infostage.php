<?php
include('all.header.php');
include('logincheck.php');
if (!isset($_GET['id'])) {
    header('Location: index');
    die();
}
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
$valideModif = false;
if ($_SESSION['connected'] === "ent" || $_SESSION['connected'] === "admin") {
    if (!($stmt = $mysqli->prepare('SELECT COUNT(*) FROM stages WHERE idEnt=? AND idStage=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('ii', $_SESSION['id'], $_GET['id']);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_result($existe);
    $stmt->fetch();
    if ($existe == 1 || $_SESSION['connected'] == "admin") {
        $valideModif = true;
    }
    $stmt->close();
}

// Requète SQL

if (!($stmt = $mysqli->prepare('SELECT mailEnt, nomStage, sujetStage,  detailsStage,
                                       prenomContactStage, nomContactStage, mailContactStage, telEnt,
                                       telSecEnt, adresseEnt, dateDebutStage, dateFinStage, dateLimiteStage, lieuStage,
                                       latStage, lngStage, dureeStage, diplome_nom, l1Stage, l2Stage, l3Stage
                                FROM stages,entreprises,diplomes
                                WHERE stages.idEnt=entreprises.idEnt
                                AND diplome_sise = filiereStage
                                AND idStage=?'))
) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('i', $_GET['id']);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($mailEnt, $nomStage, $sujetStage, $detailsStage,
    $prenomContactStage, $nomContactStage, $mailContactStage, $telEnt,
    $telSecEnt, $adresseEnt, $dateDebutStage, $dateFinStage, $dateLimiteStage, $lieuStage,
    $latStage, $lngStage, $dureeStage, $filiereStage, $l1Stage, $l2Stage, $l3Stage);
$stmt->fetch();
$stmt->close();
$niveauStage = "";
if ($l1Stage == 1) {
    $niveauStage .= "L1 ";
}
if ($l2Stage == 1) {
    $niveauStage .= "L2 ";
}
if ($l2Stage == 1) {
    $niveauStage .= "L3 ";
}
if ($niveauStage=='') {
    $niveauStage='Non précisé';
}
?>
    <div class="row panel">
        <?php if ($valideModif) {
            ?>
            <div class="row">
                <div class="large-12 text-right columns">
                    <a href="majstage?id=<?php echo $_GET['id'];?>" class="button">Modifier le stage</a>
                </div>
            </div>
        <?php
        }?>
        <div class="row">
            <div class="large-4 columns text-center">
                <img src="fichiers/profile/<?php echo md5($mailEnt) . ".png" ?>" alt="Logo de l'entreprise" onerror='this.onerror = null; this.src="./fichiers/profile/default.png"'/>
            </div>
            <div class="large-8 columns">
                <h4>Nom</h4>

                <p><em><?php echo $nomStage ?></em></p>
                <h4>Sujet</h4>

                <p><?php echo $sujetStage ?></p>

                <div class="row">
                    <div class="small-6 columns">
                        <h4>Filière</h4>

                        <p><?php echo $filiereStage ?></p>
                    </div>
                    <div class="small-6 columns">
                        <h4>Niveau</h4>

                        <p><?php echo $niveauStage ?></p>
                    </div>
                </div>
            </div>
        </div>
        <br/>

        <div class="row">
            <div class="large-6 columns">
                <h4>Détails</h4>

                <p><?php echo $detailsStage; ?></p>
                <h4>Contact</h4>

                <p><?php echo $prenomContactStage . " " . $nomContactStage . " - " . $mailContactStage ?></p>

                <p><?php echo "Téléphone : " . $telEnt;
                    if ($telSecEnt != NULL) {
                        echo " ou " . $telSecEnt;
                    }
                    ?></p>

                <p><?php echo nl2br($adresseEnt); ?></p>
            </div>
            <div class="large-6 columns">
                <div class="row">
                    <div class="large-6 columns">
                        <h4>Début du stage</h4>

                        <p><?php echo strftime("%A %#d %B %Y", strtotime($dateDebutStage)) ?></p>
                    </div>
                    <div class="large-6 columns">
                        <h4>Fin du stage</h4>

                        <p> <?php echo strftime("%A %#d %B %Y", strtotime($dateFinStage)) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <h4>Durée du stage</h4>

                        <p> <?php echo $dureeStage; ?> jours</p>
                    </div>
                    <div class="large-6 columns">
                        <h4>Contacter avant le</h4>

                        <p> <?php echo utf8_encode(strftime("%A %#d %B %Y", strtotime($dateLimiteStage))) ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <h4>Lieu du stage</h4>

                        <p><?php echo nl2br($lieuStage); ?></p>
                    </div>
                    <div class="large-6 columns">
                        <?php if (($_SESSION['connected'] == "admin") || $_SESSION['connected'] == "etud") {
                            echo '<a href="mail?cible=stage&mail=' . $mailContactStage . '" class="button">Contacter l\'entreprise pour ce stage</a>';
                        } ?>

                    </div>
                </div>


            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
                        type="text/javascript"></script>
                <div id="map" style="height: 320px"><br/></div>
                <script type="text/javascript">
                    //<![CDATA[
                    function load() {
                        if (GBrowserIsCompatible()) {
                            var map = new GMap2(document.getElementById("map"));
                            map.addControl(new GSmallMapControl());
                            map.addControl(new GMapTypeControl());
                            var center = new GLatLng(<?php echo json_encode($latStage);?>, <?php echo json_encode($lngStage);?>);
                            map.setCenter(center, 15);
                            var marker = new GMarker(center, {draggable: false});
                            map.addOverlay(marker);
                        }
                    }
                    load();
                    //]]>
                </script>
            </div>
        </div>

    </div>

<?php
include('all.footer.php');
?>