<?php
include('all.header.php');
require_once('logincheck.php');
$user = getInfos();
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
                            <span class="prefix">Afficher les stages expirés</span>
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
                            <span class="prefix">Rémunéré</span>
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
                <div class="large-12 columns">
                    <h4>Options</h4>
                    <div class="row">
                        <div class="large-3 columns">
                            <div class="row collapse">
                                <div class="small-4 columns">
                                    <span class="prefix">Année</span>
                                </div>
                                <div class="small-8 columns">
                                    <select id="annee" name="annee">
                                         <?php
                                         switch ($user->annee) {
                                             case "L1":
                                                 echo ' <option value="l1" selected>L1</option>
                                                        <option value="l2">L2</option>
                                                        <option value="l3">L3</option>';
                                                 break;
                                             case "L2":
                                                 echo ' <option value="l1">L1</option>
                                                        <option value="l2" selected>L2</option>
                                                        <option value="l3">L3</option>';
                                                 break;
                                             case "L3":
                                                 echo ' <option value="l1">L1</option>
                                                        <option value="l2">L2</option>
                                                        <option value="l3" selected>L3</option>';
                                                 break;
                                         }
                                         ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">
                            <div class="row collapse">
                                <div class="small-4 columns">
                                    <span class="prefix">Filière</span>
                                </div>
                                <div class="small-8 columns">
                                    <select id="filiere" name="filiere">
                                        <option value="spi_info">SPI - Informatique</option>
                                        <option value="spi_megp">SPI - MEGP</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">
                            <div class="row collapse">
                                <div class="small-6 columns">
                                    <span class="prefix">Début du stage</span>
                                </div>
                                <div class="small-6 columns">
                                    <input type="date" id="dateDebut" name="dateDebut" placeholder="JJ/MM/AAAA">
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">
                            <div class="row collapse">
                                <div class="small-6 columns">
                                    <span class="prefix">Durée du stage</span>
                                </div>
                                <div class="small-6 columns">
                                    <input type="number" id="duree" name="duree" placeholder="En jours">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="large-centered large-6 columns">
                    <input class="large button expand" id="envoyer" type="submit" value="Rechercher"/>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="small-12">
            <?php
            //@todo filiere
            $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
            $baseQuery = 'SELECT idStage, nomStage, lieuStage, sujetStage, dateDebutStage, dureeStage
                        FROM stages
                        WHERE (nomStage LIKE ?
                        OR sujetStage LIKE ?
                        OR detailsStage LIKE ?
                        OR competencesStage LIKE ?)';
            if (!isset($_GET['expStage']) || $_GET['expStage'] == 0) {
                $baseQuery .= " AND TO_DAYS(NOW()) < TO_DAYS(dateLimiteStage)";
            }
            if (isset($_GET['remStage']) && $_GET['remStage'] == 1) {
                $baseQuery .= " AND remuStage=1";
            }
            if (isset($_GET['annee'])) {
                switch($_GET['annee']) {
                    case "l1":
                        $baseQuery .= " AND l1Stage = 1";
                        break;
                    case "l2":
                        $baseQuery .= " AND l2Stage = 1";
                        break;
                    case "l3":
                        $baseQuery .= " AND l3Stage = 1";
                        break;
                }
            }
            if (isset($_GET['filiere'])) {
                //$baseQuery .= " AND filiereStage = \"".$_GET['filiere']."\"";
            }
            if (isset($_GET['dateDebut'])) {
                //$baseQuery .= " AND dateDebutStage = \"".$_GET['dateDebut']."\"";
            }
            if (isset($_GET['duree'])) {
                //$baseQuery .= " AND dureeStage = \"".$_GET['duree']."\"";
            }
            $baseQuery .= " ORDER BY dateDebutStage DESC";
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
                    <div class="small-12 columns text-center">
                        <h3>Pas de résultat</h3>
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