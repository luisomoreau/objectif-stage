<?php
include('all.header.php');
if ($_SESSION['connected'] !== "ent") {
    header('Location: ./');
    die();
}
$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
if (!($stmt = $mysqli->prepare('SELECT prenomContactEnt, nomContactEnt, mailEnt, adresseEnt, latEnt, lngEnt FROM Entreprises WHERE idEnt=?'))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_param('i', $_SESSION['id']);
if (!($stmt->execute())) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
$stmt->bind_result($prenomContactEnt, $nomContactEnt, $mailEnt, $adresseEnt, $latEnt, $lngEnt);
$stmt->fetch();
$stmt->close();

?>
    <div class="row">
        <div class="large-12 columns">
            <h1>Déposer un stage</h1>
        </div>
    </div>
    <form action="depotstagedb" method="POST" id="depoStage">
        <div class="row">
            <div class="large-6 columns">
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Intitulé du stage</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="text" name="nomStage" id="nomStage" maxlength="200" required/>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Prénom du contact</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="text" name="prenomContactStage" id="prenomContactStage" maxlength="50" value="<?php echo $prenomContactEnt; ?>" required/>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Nom du contact</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="text" name="nomContactStage" id="nomContactStage" maxlength="50" value="<?php echo $nomContactEnt; ?>" required/>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-6 large-3 columns">
                        <span class="prefix">Mail du contact</span>
                    </div>
                    <div class="small-6 large-9 columns">
                        <input type="email" name="mailContactStage" id="mailContactStage" maxlength="100" value="<?php echo $mailEnt; ?>" required/>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <label for="lieuStage">Lieu du stage</label>
                        <textarea name="lieuStage" id="lieuStage" maxlength="255" required><?php echo $adresseEnt; ?></textarea>
                    </div>
                </div>
                <br>
            </div>
            <div class="large-6 columns">
                <input id="lat" type="hidden" name="latStage" value=""/>
                <input id="lng" type="hidden" name="lngStage" value=""/>

                <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
                        type="text/javascript"></script>
                <div id="map" style=" height: 250px"><br/></div>
                <script type="text/javascript">
                    //<![CDATA[
                    loadmap(<?php echo $latEnt.",".$lngEnt?>);
                    //]]>
                </script>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <label for="sujetStage">Sujet du stage</label>
                <textarea name="sujetStage" id="sujetStage" maxlength="1000" required></textarea>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="large-12 columns">
                <label for="detailsStage">Details du stage</label>
                <textarea name="detailsStage" id="detailsStage" maxlength="1000" required></textarea>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="large-9 columns">
                <div class="row collapse">
                    <div class="large-3 columns">
                        <span class="prefix">Filière</span>
                    </div>
                    <div class="large-9 columns">
                        <select name="filiereStage" required>
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
                                echo ' <option value="' . $diplome_sise . '">' . $diplome_nom . '</option>';
                            }
                            $stmt->close();
                            ?>
                        </select>
                    </div>
                </div>
                <br/>

                <div class="row">

                    <div class="large-4 columns">
                        <div class="row collapse">
                            <div class="small-8 columns">
                                <span class="prefix">Stage L1</span>
                            </div>
                            <div class="small-4 columns">
                                <div class="switch">
                                    <input id="stageL1" name="stageL1" type="radio" value="0" checked>
                                    <label for="stageL1" onclick="" class="text-center">Non</label>

                                    <input id="stageL1" name="stageL1" type="radio" value="1">
                                    <label for="stageL1" onclick="" class="text-center">Oui</label>

                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="large-4 columns">
                        <div class="row collapse">
                            <div class="small-8 columns">
                                <span class="prefix">Stage L2</span>
                            </div>
                            <div class="small-4 columns">
                                <div class="switch">
                                    <input id="stageL2" name="stageL2" type="radio" value="0" checked>
                                    <label for="stageL2" onclick="" class="text-center">Non</label>

                                    <input id="stageL2" name="stageL2" type="radio" value="1">
                                    <label for="stageL2" onclick="" class="text-center">Oui</label>

                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="large-4 columns">
                        <div class="row collapse">
                            <div class="small-8 columns">
                                <span class="prefix">Stage L3</span>
                            </div>
                            <div class="small-4 columns">
                                <div class="switch">
                                    <input id="stageL3" name="stageL3" type="radio" value="0" checked>
                                    <label for="stageL3" onclick="" class="text-center">Non</label>

                                    <input id="stageL3" name="stageL3" type="radio" value="1">
                                    <label for="stageL3" onclick="" class="text-center">Oui</label>

                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row collapse">
                    <div class="small-8 columns">
                        <span class="prefix">Stage rémunéré</span>
                    </div>
                    <div class="small-4 columns">
                        <div class="switch">
                            <input id="remStage" name="remStage" type="radio" value="0" checked="">
                            <label for="remStage" onclick="" class="text-center">Non</label>
                            <input id="remStage" name="remStage" type="radio" value="1">
                            <label for="remStage" onclick="" class="text-center">Oui</label>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="large-3 columns">
                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">Date début stage</span>
                    </div>
                    <div class="large-6 columns">
                        <input type="text" class="span2 date_picker" name="dateDebut" value="01/01/2014">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">Date fin stage</span>
                    </div>
                    <div class="large-6 columns">
                        <input type="text" class="span2 date_picker" name="dateFin" value="01/01/2014">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="large-6 columns">
                        <span class="prefix">Date limite stage</span>
                    </div>
                    <div class="large-6 columns">
                        <input type="text" class="span2 date_picker" name="dateLimite" value="01/01/2014">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input class="large button expand" id="envoyer" name="submit" type="submit" value="Déposer le stage"/>
            </div>
        </div>
    </form>
<?php include('all.footer.php'); ?>