<?php
include('all.header.php');
require_once('logincheck.php');
?>
    <div class="row panel">
        <div class="row">
            <div class="small-12 columns">
                <h3>Recherche</h3>
            </div>
        </div>
        <form action="listestages" method="GET" id="recherche">
            <div class="row">

                <div class="large-6 column">
                    <input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100"
                           value="<?php if (isset($_GET['champ_rech'])) {
                               echo $_GET['champ_rech'];
                           } ?>"/>
                </div>
                <div class="large-3 column">
                    <div class="row collapse">
                        <div class="small-8 columns">
                            <span class="prefix">Afficher même les stages expirés ?</span>
                        </div>
                        <div class="small-4 columns">
                            <div class="switch">
                                <input id="expStage" name="expStage" type="radio"
                                       value="0" <?php if (!isset($_GET['expStage']) || $_GET['expStage'] == 0) {
                                    echo "checked";
                                } ?>>
                                <label for="expStage" onclick="" class="text-center">Non</label>
                                <input id="expStage" name="expStage" type="radio"
                                       value="1" <?php if (isset($_GET['expStage']) && $_GET['expStage'] == 1) {
                                    echo "checked";
                                } ?>>
                                <label for="expStage" onclick="" class="text-center">Oui</label>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="large-3 columns">
                    <div class="row collapse">
                        <div class="small-8 columns">
                            <span class="prefix">Rémunéré ?</span>
                        </div>
                        <div class="small-4 columns">
                            <div class="switch">
                                <input id="remStage" name="remStage" type="radio"
                                       value="0" <?php if (!isset($_GET['remStage']) || $_GET['remStage'] == 0) {
                                    echo "checked";
                                } ?>>
                                <label for="remStage" onclick="" class="text-center">Non</label>
                                <input id="remStage" name="remStage" type="radio"
                                       value="1" <?php if (isset($_GET['remStage']) && $_GET['remStage'] == 1) {
                                    echo "checked";
                                } ?>>
                                <label for="remStage" onclick="" class="text-center">Oui</label>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="large-centered large-6 columns">
                    <input class="large button expand" id="envoyer" type="submit" value="Rechercher"/>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <?php
            //@todo filiere
            $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
            $baseQuery = 'SELECT idStage, nomStage, lieuStage, sujetStage, dateDebutStage, dureeStage
                        FROM stages
                        WHERE (nomStage LIKE ?
                        OR sujetStage LIKE ?
                        OR detailsStage LIKE ?
                        OR competencesStage LIKE ?)';
            $user = getInfos();
            if (!isset($_GET['expStage']) || $_GET['expStage'] == 0) {
                $baseQuery .= " AND TO_DAYS(NOW()) < TO_DAYS(dateLimiteStage)";
            }
            if (isset($_GET['remStage']) && $_GET['remStage'] == 1) {
                $baseQuery .= " AND remuStage=1";
            }
            switch ($user->annee) {
                case "L1":
                    $baseQuery .= " AND l1Stage=1";
                    break;
                case "L2":
                    $baseQuery .= " AND l2Stage=1";
                    break;
                case "L3":
                    $baseQuery .= " AND l3Stage=1";
                    break;
            }
            if (!($stmt = $mysqli->prepare($baseQuery))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (isset($_GET['champ_rech'])) {
                $search = '%' . $_GET['champ_rech'] . '%';
                $stmt->bind_param('ssss', $search, $search, $search, $search);
            } else {
                $vide = '%%';
                if (!($stmt->bind_param('ssss', $vide, $vide, $vide, $vide))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
            }

            if (!($stmt->execute())) {
                echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            $stmt->bind_result($idStage, $nomStage, $lieuStage, $sujetStage, $dateDebutStage, $dureeStage);
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                echo "<table>";
                echo "<thead><tr><th>Nom du stage</th><th>Lieu</th><th>Sujet</th><th>Début du stage</th><th>Durée du stage</th><th>Options</th></tr></thead><tbody>";
                while ($stmt->fetch()) {
                    echo '<tr onclick="document.location.href=\'infostage?id=' . $idStage . '\'">';
                    echo "<td>$nomStage</td><td>$lieuStage</td><td>$sujetStage</td><td>" . utf8_encode(strftime("%#d %B %Y", strtotime($dateDebutStage))) . "</td><td>$dureeStage jours</td>";
                    echo '<td><a href="infostage?id=' . $idStage . '">Plus d\'infos</a></td>';
                    /** Mettre des petites images choc logo des languages **/
                    echo "</tr>";
                }

                echo "</tbody></table>";
            } else {
                ?>
                <div class="row">
                    <div class="small-12 columns">
                        <h4>Pas de résultat</h4>
                    </div>
                </div>
                <?php
            }

            $stmt->close();
            ?>
        </div>
    </div>



<?php
include('all.footer.php');
?>