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
                        <div class="large-2 columns">
                            <div class="row collapse">
                                <div class="small-5 columns">
                                    <span class="prefix">Année</span>
                                </div>
                                <div class="small-7 columns">
                                    <select id="annee" name="annee">
                                        <?php
                                        if (isset($user->annee)) {
                                            $select_annee = $user->annee;
                                        } else {
                                            if (isset($_GET['annee'])) {
                                                $select_annee = $_GET['annee'];
                                            } else {
                                                $select_annee = "NULL";
                                            }
                                        }
                                        switch ($select_annee) {
                                            case "L1":
                                                echo ' <option value="L1" selected>L1</option>
                                                        <option value="L2">L2</option>
                                                        <option value="L3">L3</option>';
                                                break;
                                            case "L2":
                                                echo ' <option value="L1">L1</option>
                                                        <option value="L2" selected>L2</option>
                                                        <option value="L3">L3</option>';
                                                break;
                                            case "L3":
                                                echo ' <option value="L1">L1</option>
                                                        <option value="L2">L2</option>
                                                        <option value="L3" selected>L3</option>';
                                                break;
                                            default:
                                                echo ' <option value="L1">L1</option>
                                                        <option value="L2">L2</option>
                                                        <option value="L3">L3</option>';
                                                break;
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="large-5 columns">
                            <div class="row collapse">
                                <div class="small-2 columns">
                                    <span class="prefix">Filière</span>
                                </div>
                                <div class="small-10 columns">
                                    <select id="filiere" name="filiere">
                                        <option selected="selected" disabled="disabled" value="">Filière</option>
                                        <?php
                                        $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
                                        if (!($stmt = $mysqli->prepare('SELECT diplome_sise, diplome_nom FROM diplomes WHERE diplome_active=1'))) {
                                            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                                        }
                                        if (!($stmt->execute())) {
                                            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
                                        }
                                        $stmt->bind_result($diplome_sise, $diplome_nom);
                                        $stmt->store_result();
                                        while ($stmt->fetch()) {
                                            echo '<option value="'.$diplome_sise.'"';
                                            if ((isset($_GET['filiere']) && $_GET['filiere'] == $diplome_sise) || (isset($user->filiere) && $user->filiere == $diplome_sise)) {
                                                echo ' selected';
                                            }
                                            echo '>'.$diplome_nom.'</option>';
                                        }
                                        $stmt->close();
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="large-3 columns">
                            <div class="row collapse">
                                <div class="small-7 columns">
                                    <span class="prefix">Début du stage <b><</b></span>
                                </div>
                                <div class="small-5 columns">
                                    <input type="text" class="date_picker" id="dateDebut" name="dateDebut" placeholder="JJ/MM/AAAA" value="<?php if (isset($_GET['dateDebut'])) echo $_GET['dateDebut']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="large-2 columns">
                            <div class="row collapse">
                                <div class="small-8 columns">
                                    <span class="prefix">Durée du stage <b>></b></span>
                                </div>
                                <div class="small-4 columns">
                                    <input type="number" id="duree" name="duree" placeholder="jours" value="<?php if (isset($_GET['duree'])) echo $_GET['duree']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>

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
                switch ($_GET['annee']) {
                    case "L1":
                        $baseQuery .= " AND l1Stage = 1";
                        break;
                    case "L2":
                        $baseQuery .= " AND l2Stage = 1";
                        break;
                    case "L3":
                        $baseQuery .= " AND l3Stage = 1";
                        break;
                }
            }
            if (isset($_GET['filiere'])) {
                $baseQuery .= " AND filiereStage = \"".$_GET['filiere']."\"";
            }
            if (isset($_GET['dateDebut'])) {
                $baseQuery .= " AND dateDebutStage>STR_TO_DATE(?, '%d/%m/%Y')";
            }
            if (isset($_GET['duree']) && ($_GET['duree']!=='')) {
                $baseQuery .= " AND dureeStage > ? ";
            }
            $baseQuery .= " ORDER BY dateDebutStage DESC";
            //echo $baseQuery;
            if (!($stmt = $mysqli->prepare($baseQuery))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (isset($_GET['champ_rech'])) {
                $search = '%' . $_GET['champ_rech'] . '%';
                if (isset($_GET['duree']) && ($_GET['duree']!=='')) {
                    if (isset($_GET['dateDebut'])) {
                        $stmt->bind_param('sssssi', $search, $search, $search, $search,  $_GET['dateDebut'], $_GET['duree']);
                    } else {
                        $stmt->bind_param('ssssi', $search, $search, $search, $search, $_GET['duree']);
                    }
                } else {
                    if (isset($_GET['dateDebut'])) {
                        $stmt->bind_param('sssss', $search, $search, $search, $search,$_GET['dateDebut']);
                    } else {
                        $stmt->bind_param('ssss', $search, $search, $search, $search);
                    }

                }

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
                echo '<table style="width: 100%">';
                echo "<thead><tr><th>Nom du stage</th><th>Lieu</th><th>Sujet</th><th>Début du stage</th><th>Durée du stage</th><th>Options</th></tr></thead><tbody>";
                while ($stmt->fetch()) {
                    echo '<tr onclick="document.location.href=\'infostage?id=' . $idStage . '\'">';
                    echo "<td>$nomStage</td><td>$lieuStage</td><td>$sujetStage</td><td>" . utf8_encode(strftime("%#d %B %Y", strtotime($dateDebutStage))) . "</td><td>$dureeStage jours</td>";
                    echo '<td><a href="infostage?id=' . $idStage . '">Plus d\'infos</a></td>';
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