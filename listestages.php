<?php
include ('all.header.php');
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
                <input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php if (isset($_GET['champ_rech'])) { echo $_GET['champ_rech']; } ?>" />
            </div>
            <div class="large-3 column">
                <div class="row collapse">
                    <div class="small-8 columns">
                        <span class="prefix">Afficher même les stages expirés ?</span>
                    </div>
                    <div class="small-4 columns">
                        <div class="switch">
                            <input id="expStage" name="expStage" type="radio" value="0" <?php if(!isset($_GET['expStage']) || $_GET['expStage']==0) { echo "checked";}?>>
                            <label for="expStage" onclick="" class="text-center">Non</label>
                            <input id="expStage" name="expStage" type="radio" value="1" <?php if(isset($_GET['expStage']) && $_GET['expStage']==1) { echo "checked";}?>>
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
                            <input id="remStage" name="remStage" type="radio" value="0" <?php if(!isset($_GET['remStage']) || $_GET['remStage']==0) { echo "checked";}?>>
                            <label for="remStage" onclick="" class="text-center">Non</label>
                            <input id="remStage" name="remStage" type="radio" value="1" <?php if(isset($_GET['remStage']) && $_GET['remStage']==1) { echo "checked";}?>>
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
            // Chargement des paramètres de la DB @todo SQL
            if (isset($_GET['champ_rech'])) {
                $query = "SELECT * FROM stages WHERE (nomStage LIKE '%".$_GET['champ_rech']."%'";
            } else {
                $query = "SELECT * FROM stages WHERE (nomStage LIKE '%%'";
            }

            //echo $_GET[champ_rech];
            if (isset($_GET['champ_rech'])) {
                $query.=" OR sujetStage LIKE '%".$_GET['champ_rech']."%'";
                $query.=" OR detailsStage LIKE '%".$_GET['champ_rech']."%')";
            } else {
                $query.=")";
            }
            if (isset($_GET['htmlcssStage'])) {
                $query.=" AND htmlcssStage='1'";
            }
            if (isset($_GET['phpStage'])) {
                $query.=" AND phpStage='1'";
            }
            if (isset($_GET['sqlStage'])) {
                $query.=" AND sqlStage='1'";
            }
            if (isset($_GET['javaStage'])) {
                $query.=" AND javaStage='1'";
            }
            if (isset($_GET['cStage'])) {
                $query.=" AND cStage='1'";
            }
            if (isset($_GET['csStage'])) {
                $query.=" AND csStage='1'";
            }
            if (isset($_GET['licenceEtud']) && ($_GET['licenceEtud']!=="Niveau")) {
                $query.=" AND typeStage='$_GET[licenceEtud]'";
            }
            if (isset($_GET['remStage'])) {
                $query.=" AND remuStage='1'";
            }
            if (!isset($_GET['expStage'])) {
                $query.=" AND TO_DAYS(NOW()) < TO_DAYS(dateLimiteStage)";
            }

            // Connection SQL
            $dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));
            // Exécution de la requète pour les colones
            $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
            // Affichage des colones
            $data = mysqli_fetch_assoc($result);
            if ($data == NULL) {
                echo "Aucun résultat";
            } else {
                echo "<table>";
                echo "<thead><tr><th>Nom du stage</th><th>Lieu</th><th>Sujet</th><th>Début du stage</th><th>Durée du stage</th><th>Options</th></tr></thead><tbody>";
                // Exécution de la requète pour les valeures
                $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
                // Remplissage du tableau
                while($data = mysqli_fetch_assoc($result)) {
                    echo '<tr onclick="document.location.href=\'infostage?id='.$data['idStage'].'\'">';
                    echo "<td>$data[nomStage]</td><td>$data[lieuStage]</td><td>$data[sujetStage]</td><td>".utf8_encode (strftime("%#d %B %Y",strtotime($data['dateDebutStage'])))."</td><td>$data[dureeStage] jours</td>";
                    echo '<td><a href="infostage?id='.$data['idStage'].'">Plus d\'infos</a></td>';
                    /** Mettre des petites images choc logo des languages **/
                    echo "</tr>";
                }
                // On ferme le tableau
                echo "</tbody></table>";
            }
            ?>
        </div>
    </div>



<?php
include ('all.footer.php');
?>