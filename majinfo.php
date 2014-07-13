<?php
    include('all.header.php');
    require_once('logincheck.php');

    $mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
    if ($_SESSION['connected'] === "etud" || (isset($_GET['idEtud']) && $_SESSION['connected'] === "admin")) {
        // Requète SQL
        if ($_SESSION['connected'] === "admin") {
            //$query = "SELECT * FROM Etudiants WHERE idEtud='$_GET[idEtud]'";
            if (!($stmt = $mysqli->prepare('SELECT * FROM etudiants WHERE idEtud=?'))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            $stmt->bind_param('i', $_GET['idEtud']);
        } else {
            //$query = "SELECT * FROM Etudiants WHERE mailEtud='$_SESSION[identifiant]'";
            $stmt = $mysqli->prepare('SELECT mailEtud, mailPersoEtud, nomEtud, prenomEtud, trouveStageEtud, anneeEtud, filiereEtud, civiliteEtud, naissanceEtud, telEtud, telSecEtud
                                            FROM etudiants
                                            WHERE userEtud=?');
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
            $stmt->bind_param('s', $_SESSION['identifiant']);
            echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $stmt->execute();
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        $stmt->bind_result($mailEtud, $mailPersoEtud, $nomEtud, $prenomEtud, $trouveStageEtud, $licenceEtud, $filiereEtud, $civiliteEtud, $naissanceEtud, $telEtud, $telSecEtud);
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        $stmt->fetch();
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        $stmt->close();
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo '<section class="row">
            <div class="small-12 columns">
                <h1>';
        if ($_SESSION['connected'] === "admin") {
            echo '<a href="supprimercompte?idEtud=' . $_GET['idEtud'] . '"><button class="float_r" onclick="return confirm(\'Êtes-vous sur de vouloir supprimer définitivement ce compte?\');">Supprimer le compte</button></a>';
        }
        echo '</h1>
            </div>
         </section>';
        ?>
    <form action="majinfodb" method="POST" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEtud') && checkPass('mdpEtud','mdpEtud2'))">
        <div class="row">
            <div class="large-4 columns">
                    <img src="fichiers/profile/<?php echo md5($_SESSION['identifiant']).".png" ?>" alt="Photo de profile" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"' />
                <br />
                <label for="profilpic">Modifier votre photo de profil</label>
                <input type="file" name="profilpic" id="profilpic"/>
            </div>
            <div class="large-8 columns">
                <?php if ($_SESSION['connected'] === "admin") { echo '<input type="hidden" name="mailEtud" value="'.$mailEtud.'" />';} ?>

                <div class="row collapse">
                    <div class="small-3 columns">
                        <span class="prefix">Mail UNC</span>
                    </div>
                    <div class="small-9 columns">
                        <input type="text"  name="mailEtud" id="mailEtud" maxlength="100" required="required" value="<?php echo $mailEtud;?>" disabled="disabled">
                    </div>
                </div>

                <div class="row">
                    <div class="large-2 columns">
                        <div class="row collapse">
                            <div class="small-6 columns">
                                <span class="prefix">Civilité</span>
                            </div>
                            <div class="small-6 columns">
                                <input type="text" maxlength="6" name="civiliteEtud" id="civiliteEtud" required="required" value="<?php echo $civiliteEtud;?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="large-4 columns">
                        <div class="row collapse">
                            <div class="small-3 columns">
                                <span class="prefix">Prenom</span>
                            </div>
                            <div class="small-9 columns">
                                <input type="text" name="prenomEtud" id="prenomEtud" maxlength="50" required="required" value="<?php echo $prenomEtud;?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-3 columns">
                                <span class="prefix">Nom</span>
                            </div>
                            <div class="small-9 columns">
                                <input type="text" name="nomEtud" id="nomEtud" maxlength="50" required="required" value="<?php echo $nomEtud;?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                </div>

<!--                <div class="row collapse">-->
<!--                    <div class="small-3 columns">-->
<!--                        <span class="prefix">Prenom</span>-->
<!--                    </div>-->
<!--                    <div class="small-9 columns">-->
<!--                        <input type="text" name="prenomEtud" id="prenomEtud" maxlength="50" required="required" value="--><?php //echo $prenomEtud;?><!--" disabled="disabled">-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class="row collapse">-->
<!--                    <div class="small-3 columns">-->
<!--                        <span class="prefix">Nom</span>-->
<!--                    </div>-->
<!--                    <div class="small-9 columns">-->
<!--                        <input type="text" name="nomEtud" id="nomEtud" maxlength="50" required="required" value="--><?php //echo $nomEtud;?><!--" disabled="disabled">-->
<!--                    </div>-->
<!--                </div>-->

                <div class="row">
                    <div class="large-2 columns">
                        <div class="row collapse">
                            <div class="small-6 columns">
                                <span class="prefix">Année</span>
                            </div>
                            <div class="small-6 columns">
                                <input type="text" name="licenceEtud" id="licenceEtud" required="required" value="<?php echo $licenceEtud;?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="large-10 columns">
                        <div class="row collapse">
                            <div class="small-2 columns">
                                <span class="prefix">Filière</span>
                            </div>
                            <div class="small-10 columns">
                                <input type="text" name="filiereEtud" id="filiereEtud" required="required" value="<?php echo $filiereEtud;?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-5 columns">
                                <span class="prefix">Tel UNC</span>
                            </div>
                            <div class="small-7 columns">
                                <input type="tel" maxlength="6" name="telEtud" id="telEtud" required="required" value="<?php echo $telEtud;?>" disabled="disabled">
                            </div>
                        </div>
                    </div>
                    <div class="large-6 columns">
                        <div class="row collapse">
                            <div class="small-5 columns">
                                <span class="prefix">Tel personnel</span>
                            </div>
                            <div class="small-7 columns">
                                <input placeholder="" type="tel" maxlength="6" name="telSecEtud" id="telSecEtud" onkeyup="verif_nombre(this)" value="<?php echo $telSecEtud;?>"/>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row collapse">
                    <div class="small-3 columns">
                        <span class="prefix">Mail personnel</span>
                    </div>
                    <div class="small-9 columns">
                        <input type="email" name="mailPersoEtud" id="mailPersoEtud" maxlength="100" value="<?php echo $mailPersoEtud;?>">
                    </div>
                </div>

                <div class="row collapse">
                    <div class="small-3 columns">
                        <span class="prefix">Date de naissance</span>
                    </div>
                    <div class="small-9 columns">
                        <input type="date" name="naissanceEtud" id="naissanceEtud" placeholder="AAAA-MM-JJ">
                    </div>
                </div>
            </div>
        </div>


        <div class="row ">
            <div class="small-12 large-3 large-centered columns">
                <div class="row collapse">
                    <div class="small-8 columns">
                        <span class="prefix">Avez-vous trouvé un stage?</span>
                    </div>
                    <div class="small-4 columns">
                        <div class="switch">
                            <input id="trouveStageEtud" name="trouveStageEtud" type="radio" value="0" <?php if($trouveStageEtud=="0") { echo 'checked';} ?>>
                            <label for="trouveStageEtud" onclick="" class="text-center">Non</label>

                            <input id="trouveStageEtud" name="trouveStageEtud" type="radio" value="1" <?php if($trouveStageEtud=="1") { echo 'checked';} ?>>
                            <label for="trouveStageEtud" onclick="" class="text-center">Oui</label>

                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!--        <div class="row">-->
<!--            <div class="small-12 large-3 large-centered columns">-->
<!--                <div class="row collapse">-->
<!--                    <div class="small-8 columns">-->
<!--                        <span class="prefix">Avez-vous trouvé un stage?</span>-->
<!--                    </div>-->
<!--                    <div class="small-4 columns">-->
<!--                        <select name="trouveStageEtud" id="trouveStageEtud">-->
<!--                            <option>Non</option>-->
<!--                            <option>Oui</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <br />
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input class="large button expand" id="envoyer" type="submit" value="Enregistrer mes informations"/>
            </div>
        </div>

    </form>
<?php
    } else {
        if ($_SESSION['connected'] == "ent" || (isset($_GET['idEnt']) && $_SESSION['connected'] === "admin")) {
            var_dump($_SESSION['connected']);
            // Requète SQL
            if ($_SESSION['connected'] === "admin") {
                $query = "SELECT * FROM Entreprises WHERE idEnt='$_GET[idEnt]'";
            } else {
                $query = "SELECT * FROM Entreprises WHERE mailEnt='$_SESSION[identifiant]'";
            }
            // Exécution de la requète
            $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
            // Affichage des resultats
            $data = mysqli_fetch_assoc($result);
            var_dump($_SESSION['connected']);
            $nomEnt=$data[nomEnt];
            $mailEnt=$data[mailEnt];
            $nomContactEnt=$data[nomContactEnt];
            $prenomContactEnt=$data[prenomContactEnt];
            $telEnt=$data[telEnt];
            $adresseEnt=$data[adresseEnt];
            $telSecEnt=$data[telSecEnt];
            ?>
            <h1>Modif entreprise<?php if ($_SESSION['connected'] === "admin") echo '<a href="supprimercompte?idEnt='.$_GET['idEnt'].'"><button class="float_r" onclick="return confirm(\'Êtes-vous sur de vouloir supprimer définitivement ce compte?\');">Supprimer le compte</button></a>'?></h1>
            <form action="majinfodb" method="POST" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEnt') && checkPass('mdpEnt','mdpEnt2'))">
                <div class="col_13 float_l">
                    <?php if ($_SESSION['connected'] === "admin") echo '<input type="hidden" name="mailEnt" value='.$mailEnt.' />'; ?>
                    <label for="profilpic">Modifier le logo de l'entreprise :<br />
                        <img src="fichiers/profile/<?php echo md5($mailEnt).".png" ?>" alt="Logo de l'entreprise" class="img_float_l img_frame" id="logo" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"'/>
                    </label>
                    <input type="file" name="profilpic" id="profilpic"/>
                    <div class="cleaner h10"></div>

                    <label for="nomEnt">Nom de l'entreprise</label>
                    <input type="text" name="nomEnt" id="nomEnt" maxlength="100" required="required" value="<?php echo $nomEnt;?>"/>
                    <div class="cleaner h10"></div>

                    <label for="mailEnt">Mail entreprise (Identifiant)</label>
                    <input type="email" name="mailEnt" id="mailEnt" maxlength="100" required="required" value="<?php echo $mailEnt;?>"/>
                    <div class="cleaner h10"></div>

                    <label for="mdpEnt">Mot de passe</label>
                    <input type="password" name="mdpEnt" id="mdpEnt" maxlength="25" required="required" value="Defaut123" onkeyup="checkPatern('mdpEnt'); return false;"/>
                    <span id="confirmPatern" class="confirmPatern"></span>
                    <div class="cleaner h10"></div>

                    <label for="mdpEnt2">Confirmer mot de passe</label>
                    <input type="password" name="mdpEnt2" id="mdpEnt2" maxlength="25" required="required" value="Defaut123" onkeyup="checkPass('mdpEnt','mdpEnt2'); return false;"/>
                    <span id="confirmMessage" class="confirmMessage"></span>
                    <div class="cleaner h10"></div>

                    <label for="prenomContactEnt">Prenom de contact</label>
                    <input type="text" name="prenomContactEnt" id="prenomContactEnt" maxlength="50" required="required" value="<?php echo $prenomContactEnt;?>"/>
                    <div class="cleaner h10"></div>

                    <label for="nomContactEnt">Nom de contact</label>
                    <input type="text" name="nomContactEnt" id="nomContactEnt" maxlength="50" required="required" value="<?php echo $nomContactEnt;?>"/>
                    <div class="cleaner h10"></div>
                </div>

                <div class="col_23 float_r">
                    <label>Localisez votre entreprise : </label>
                    <input id="lat" type="hidden" name="latEnt" value="<?php echo $data[latEnt];?>"/>
                    <input id="lng" type="hidden" name="lngEnt" value="<?php echo $data[lngEnt];?>" />
                    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
                            type="text/javascript"></script>
                    <div id="map" style="width: 516px; height: 320px"><br/></div>
                    <script type="text/javascript">
                        //<![CDATA[
                        loadmap(<?php echo json_encode($data[latEnt]);?>,<?php echo json_encode($data[lngEnt]);?>);
                        //]]>
                    </script>

                    <div class="cleaner h20"></div>
                    <label for="telEnt">Téléphone </label>
                    <input placeholder="Principal" type="tel" maxlength="6" name="telEnt" id="telEnt" value="<?php echo $telEnt;?>" onkeyup="verif_nombre(this)" required="required" />
                    <input placeholder="Secondaire" type="tel" maxlength="6" name="telSecEnt" id="telSecEnt" value="<?php echo $telSecEnt;?>" onkeyup="verif_nombre(this)" />
                    <div class="cleaner h10"></div>

                    <label for="adresseEnt">Adresse</label>
                    <textarea name="adresseEnt" id="adresseEnt" maxlength="255" required="required"><?php echo $adresseEnt;?></textarea>

                </div>
                <div class="cleaner h40"></div>
                <div class="centrer"><button type="submit">Mettre à jour mes informations</button></div>
            </form>
        <?php
        } else if ($_SESSION['connected'] === "admin") {
            // Requète SQL
            $query = "SELECT * FROM Administrateurs WHERE mailAdmin='$_SESSION[identifiant]'";
            // Exécution de la requète
            $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
            // Affichage des resultats
            $data = mysqli_fetch_assoc($result);

            $nomAdmin=$data[nomAdmin];
            $prenomAdmin=$data[prenomAdmin];
            $mailAdmin=$data[mailAdmin];
            ?>
            <h1>Profil Administrateur</h1>
            <form action="majinfodb" method="POST" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEtud') && checkPass('mdpEtud','mdpEtud2'))">
                <div class="col_23 float_l">
                    <label for="mailAdmin">Identifiant (Non modifiable)</label>
                    <input placeholder="<?php echo $mailAdmin;?>" type="text" name="mailAdmin" id="mailAdmin" maxlength="100" required="required" disabled="disabled"/>
                    <div class="cleaner h10"></div>

                    <label for="mdpAdmin">Mot de passe (Un chiffre, une majuscule, une minuscule, minimum 6 caractères)</label>
                    <input type="password" name="mdpAdmin" id="mdpAdmin" maxlength="25" required="required" onkeyup="checkPatern('mdpAdmin'); return false;" value="Defaut123"/>
                    <span id="confirmPatern" class="confirmPatern"></span>
                    <div class="cleaner h10"></div>

                    <label for="mdpAdmin2">Confirmer mot de passe</label>
                    <input type="password" name="mdpAdmin2" id="mdpAdmin2" maxlength="25" required="required" onkeyup="checkPass('mdpAdmin','mdpAdmin2'); return false;" value="Defaut123"/>
                    <span id="confirmMessage" class="confirmMessage"></span>
                    <div class="cleaner h10"></div>

                    <label for="prenomAdmin">Prenom</label>
                    <input type="text" name="prenomAdmin" id="prenomAdmin" maxlength="50" required="required" value="<?php echo $prenomAdmin;?>"/>
                    <div class="cleaner h10"></div>

                    <label for="nomAdmin">Nom</label>
                    <input type="text" name="nomAdmin" id="nomAdmin" maxlength="50" required="required" value="<?php echo $nomAdmin;?>" />
                    <div class="cleaner h10"></div>

                </div>

                <div class="col_13 float_r">
                    <label for="profilpic" >Modifier ma photo de profile - <a href="javascript:window.location.reload()">Actualiser</a><br />
                        <img src="fichiers/profile/<?php echo md5($mailAdmin).".png" ?>" alt="Photo de profile" class="img_float_l img_frame" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"' />
                    </label>
                    <input type="file" name="profilpic" id="profilpic"/>
                    <div class="cleaner h10"></div>
                </div>
                <div class="cleaner h40"></div>
                <div class="centrer">
                    <button id="envoyer" type="submit">Enregistrer mes informations</button>
                </div>
            </form>

        <?php
        }
    }
    include('all.footer.php');