<?php
include('all.header.php');
include('logincheck.php');
if (!isset($_GET['id'])) {
    header('Location: index');
    die();
}
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare('SELECT nomEnt, mailEnt, prenomContactEnt, nomContactEnt, telEnt, telSecEnt, adresseEnt, latEnt, lngEnt, valideEnt
                                FROM entreprises WHERE idEnt=?'))
) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('i', $_GET['id']);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($nomEnt, $mailEnt, $prenomContactEnt, $nomContactEnt, $telEnt, $telSecEnt, $adresseEnt, $latEnt, $lngEnt, $valideEnt);
$stmt->fetch();
$stmt->close();
?>
    <div class="row panel">
        <div class="row">

            <div class="large-4 columns text-center">
                <img src="fichiers/profile/<?php echo md5($mailEnt) . ".png" ?>" alt="Logo de l'entreprise" onerror='this.onerror = null; this.src="./fichiers/profile/default.png"'/>
            </div>
            <div class="large-6 columns">
                <h4>Nom</h4>

                <p><em><?php echo $nomEnt; ?></em></p>
                <h4>Contact</h4>

                <p><strong>Nom du contact</strong>: <?php echo $prenomContactEnt . " " . $nomContactEnt; ?></p>

                <p><strong>Téléphone</strong>:
                    <?php echo $telEnt;
                    if ($telSecEnt != NULL) {
                        echo " - " . $telSecEnt;
                    }
                    ?></p>

                <p><strong>E-mail</strong>: <?php echo $mailEnt;
                    if (($_SESSION['connected'] == "admin") || $_SESSION['connected'] == "etud") {
                        echo ' - <a href="mail?cible=ent&mail=' . $mailEnt . '">Contacter l\'entreprise</a></p>';
                    } ?>
                <h4>Adresse</h4>

                <p><?php echo nl2br($adresseEnt); ?></p>
            </div>
            <div class="large-2 columns">
                <?php
                    if ($_SESSION['connected'] == "admin") {
                        if ($valideEnt == 0) {
                            echo '<a href="validerent?idEnt='.$_GET['id'] .'"class="button">Valider l\'entreprise</a>';
                        }
                        echo '<a href="majinfo?idEnt='.$_GET['id'] .'"class="button">Modifier l\'entreprise</a>';
                    }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns">
                <script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU" type="text/javascript"></script>
                <div id="map" style="height: 320px"><br/></div>
                <script type="text/javascript">
                    function load() {
                        if (GBrowserIsCompatible()) {
                            var map = new GMap2(document.getElementById("map"));
                            map.addControl(new GSmallMapControl());
                            map.addControl(new GMapTypeControl());
                            var center = new GLatLng(<?php echo json_encode($latEnt);?>, <?php echo json_encode($lngEnt);?>);
                            map.setCenter(center, 15);
                            var marker = new GMarker(center, {draggable: false});
                            map.addOverlay(marker);
                        }
                    }
                    load();
                </script>
            </div>
        </div>
    </div>

<?php
include('all.footer.php');
?>