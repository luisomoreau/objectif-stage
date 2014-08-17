<?php
include('all.header.php');
include('logincheck.php');
if (($_SESSION['connected'] == 'ent' || $_SESSION['connected'] == 'admin') && isset($_GET['id'])) {
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);

    if (!($stmt = $mysqli->prepare('SELECT mailEtud, mailPersoEtud, nomEtud, prenomEtud, trouveStageEtud, anneeEtud, diplome_nom, civiliteEtud, naissanceEtud, telEtud, telSecEtud, userEtud
                                            FROM etudiants, diplomes
                                            WHERE idEtud=?
                                            AND diplome_sise = filiereEtud'))
    ) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('i', $_GET['id']);

    $stmt->execute();
    $stmt->bind_result($mailEtud, $mailPersoEtud, $nomEtud, $prenomEtud, $trouveStageEtud, $licenceEtud, $filiereEtud, $civiliteEtud, $naissanceEtud, $telEtud, $telSecEtud, $userEtud);
    $stmt->fetch();
    $stmt->close();
    if ($mailEtud == '') {
        realDie();
    }
    ?>
    <div class="row">
        <div class="large-4 columns text-center">
            <img src="fichiers/profile/<?php echo md5($userEtud) . ".png" ?>" alt="Photo de profile" onerror='this.onerror = null; this.src="./fichiers/profile/default.png"'/>
        </div>
        <div class="large-8 columns">
            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix">Mail UNC</span>
                </div>
                <div class="small-9 columns">
                    <input type="text" id="mailEtud" maxlength="100" required="required" value="<?php echo $mailEtud; ?>" disabled="disabled">
                </div>
            </div>

            <div class="row">
                <div class="large-2 columns">
                    <div class="row collapse">
                        <div class="small-6 columns">
                            <span class="prefix">Civilité</span>
                        </div>
                        <div class="small-6 columns">
                            <input type="text" maxlength="6" name="civiliteEtud" id="civiliteEtud" required="required" value="<?php echo $civiliteEtud; ?>" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="large-4 columns">
                    <div class="row collapse">
                        <div class="small-3 columns">
                            <span class="prefix">Prenom</span>
                        </div>
                        <div class="small-9 columns">
                            <input type="text" name="prenomEtud" id="prenomEtud" maxlength="50" required="required" value="<?php echo $prenomEtud; ?>" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="large-6 columns">
                    <div class="row collapse">
                        <div class="small-3 columns">
                            <span class="prefix">Nom</span>
                        </div>
                        <div class="small-9 columns">
                            <input type="text" name="nomEtud" id="nomEtud" maxlength="50" required="required" value="<?php echo $nomEtud; ?>" disabled="disabled">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="large-2 columns">
                    <div class="row collapse">
                        <div class="small-6 columns">
                            <span class="prefix">Année</span>
                        </div>
                        <div class="small-6 columns">
                            <input type="text" name="licenceEtud" id="licenceEtud" required="required" value="<?php echo $licenceEtud; ?>" disabled="disabled">
                        </div>
                    </div>
                </div>
                <div class="large-10 columns">
                    <div class="row collapse">
                        <div class="small-2 columns">
                            <span class="prefix">Filière</span>
                        </div>
                        <div class="small-10 columns">
                            <input type="text" name="filiereEtud" id="filiereEtud" required="required" value="<?php echo $filiereEtud; ?>" disabled>
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
                            <input type="tel" maxlength="6" name="telEtud" id="telEtud" required="required" value="<?php echo $telEtud; ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="large-6 columns">
                    <div class="row collapse">
                        <div class="small-5 columns">
                            <span class="prefix">Tel personnel</span>
                        </div>
                        <div class="small-7 columns">
                            <input placeholder="" type="tel" maxlength="6" name="telSecEtud" id="telSecEtud" onkeyup="verif_nombre(this)" value="<?php echo $telSecEtud; ?>" disabled/>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix">Mail personnel</span>
                </div>
                <div class="small-9 columns">
                    <input type="email" name="mailPersoEtud" id="mailPersoEtud" maxlength="100" value="<?php echo $mailPersoEtud; ?>" disabled>
                </div>
            </div>

            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix">Date de naissance</span>
                </div>
                <div class="small-9 columns">
                    <input type="text" name="naissanceEtud" id="naissanceEtud" class="date_picker" value="<?php echo date("d/m/Y", strtotime($naissanceEtud)); ?>" maxlength="10" disabled>
                </div>
            </div>
        </div>
    </div>


    <div class="row ">
        <div class="large-12 text-center columns">
            <?php if ($trouveStageEtud == "1") { ?>
                <h3>Je suis à la recherche d'un stage</h3>
            <?php } else { ?>
                <h3>J'ai trouvé un stage</h3>
            <?php } ?>
        </div>
    </div>
    <br/>
    <?php
    if ($_SESSION['connected'] === "admin") {
        ?>
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <a class="large button expand" href="majinfo?idEtud=<?php echo $_GET['id'] ?>">Modifier ce compte</a>
            </div>
        </div>
    <?php
    }
    ?>

<?php
} else {
    realDie();
}