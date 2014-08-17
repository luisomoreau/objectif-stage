<?php
include('all.header.php');
if ($_SESSION['connected'] !== "ent" && $_SESSION['connected'] !== "admin") {
    header('Location: /');
    die();
}

$mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
$stmt = $mysqli->prepare('SELECT idEnt, nomStage, prenomContactStage, nomContactStage, lieuStage, latStage, lngStage,
                            dateDebutStage, dateFinStage, dateLimiteStage, sujetStage, detailsStage, mailContactStage,lieuStage, idStage, filiereStage, l1Stage, l2Stage, l3Stage, remuStage
                            FROM stages WHERE idStage=?');
$stmt->bind_param('i', $_GET['id']);
$stmt->execute();
$stmt->bind_result($idEnt, $nomStage, $prenomContactStage, $nomContactStage, $lieuStage, $latStage, $lngStage,
    $dateDebutStage, $dateFinStage, $dateLimiteStage, $sujetStage, $detailsStage, $mailContactStage, $lieuStage, $idStage, $filiereStage, $l1Stage, $l2Stage, $l3Stage, $remuStage);
$stmt->fetch();
$stmt->close();


if ($idEnt !== $_SESSION['id'] && $_SESSION['connected'] !== "admin" || $idStage == null) {
    header('Location: messtages');
    die();
}


?>
    <div class="row">
        <div class="large-12 columns">
            <h1>Déposer un stage</h1>
        </div>
    </div>
<form action="majstagedb" method="POST" id="depoStage">
    <input type="hidden" name="idStage" value="<?php echo $_GET['id']; ?>">
<div class="row">
    <div class="large-6 columns">
        <div class="row collapse">
            <div class="small-6 large-3 columns">
                <span class="prefix">Intitulé du stage</span>
            </div>
            <div class="small-6 large-9 columns">
                <input type="text" name="nomStage" id="nomStage" maxlength="200" value="<?php echo $nomStage; ?>" required/>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-6 large-3 columns">
                <span class="prefix">Prénom du contact</span>
            </div>
            <div class="small-6 large-9 columns">
                <input type="text" name="prenomContactStage" id="prenomContactStage" maxlength="50" value="<?php echo $prenomContactStage; ?>" required/>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-6 large-3 columns">
                <span class="prefix">Nom du contact</span>
            </div>
            <div class="small-6 large-9 columns">
                <input type="text" name="nomContactStage" id="nomContactStage" maxlength="50" value="<?php echo $nomContactStage; ?>" required/>
            </div>
        </div>
        <div class="row collapse">
            <div class="small-6 large-3 columns">
                <span class="prefix">Mail du contact</span>
            </div>
            <div class="small-6 large-9 columns">
                <input type="email" name="mailContactStage" id="mailContactStage" maxlength="100" value="<?php echo $mailContactStage; ?>" required/>
            </div>
        </div>
        <div class="row">
            <div class="large-12 columns">
                <label for="lieuStage">Lieu du stage</label>
                <textarea name="lieuStage" id="lieuStage" maxlength="255" required><?php echo $lieuStage; ?></textarea>
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
            loadmap(<?php echo $latStage.",".$lngStage?>);
            //]]>
        </script>
    </div>
</div>
<div class="row">
    <div class="large-12 columns">
        <label for="sujetStage">Sujet du stage</label>
        <textarea name="sujetStage" id="sujetStage" maxlength="1000" required><?php echo $sujetStage; ?></textarea>
    </div>
</div>
<br>

<div class="row">
    <div class="large-12 columns">
        <label for="detailsStage">Details du stage</label>
        <textarea name="detailsStage" id="detailsStage" maxlength="1000" required><?php echo $detailsStage; ?></textarea>
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
                        if ($filiereStage == $diplome_sise) {
                            echo ' <option value="' . $diplome_sise . '" selected>' . $diplome_nom . '</option>';
                        } else {
                            echo ' <option value="' . $diplome_sise . '">' . $diplome_nom . '</option>';
                        }

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
                            <input id="stageL1" name="stageL1" type="radio" value="0" <?php if ($l1Stage == 0) echo 'checked'; ?>>
                            <label for="stageL1" onclick="" class="text-center">Non</label>

                            <input id="stageL1" name="stageL1" type="radio" value="1" <?php if ($l1Stage == 1) echo 'checked'; ?>>
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
                            <input id="stageL2" name="stageL2" type="radio" value="0" <?php if ($l2Stage == 0) echo 'checked'; ?>>
                            <label for="stageL2" onclick="" class="text-center">Non</label>

                            <input id="stageL2" name="stageL2" type="radio" value="1" <?php if ($l2Stage == 1) echo 'checked'; ?>>
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
                            <input id="stageL3" name="stageL3" type="radio" value="0" <?php if ($l3Stage == 0) echo 'checked'; ?>>
                            <label for="stageL3" onclick="" class="text-center">Non</label>

                            <input id="stageL3" name="stageL3" type="radio" value="1" <?php if ($l3Stage == 1) echo 'checked'; ?>>
                            <label for="stageL3" onclick="" class="text-center">Oui</label>

                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row collapse">
            <div class="small-8 columns">
                <span class="prefix">Stage rémunéré</span>
            </div>
            <div class="small-4 columns">
                <div class="switch">
                    <input id="remStage" name="remStage" type="radio" value="0" <?php if ($remuStage == 0) echo 'checked'; ?>>
                    <label for="remStage" onclick="" class="text-center">Non</label>
                    <input id="remStage" name="remStage" type="radio" value="1" <?php if ($remuStage == 1) echo 'checked'; ?>>
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
                <input type="text" class="span2 date_picker" name="dateDebut" value="<?php echo strftime("%d/%m/%Y", strtotime($dateDebutStage)); ?>">
            </div>
        </div>
        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">Date fin stage</span>
            </div>
            <div class="large-6 columns">
                <input type="text" class="span2 date_picker" name="dateFin" value="<?php echo strftime("%d/%m/%Y", strtotime($dateFinStage)); ?>">
            </div>
        </div>
        <div class="row collapse">
            <div class="large-6 columns">
                <span class="prefix">Date limite stage</span>
            </div>
            <div class="large-6 columns">
                <input type="text" class="span2 date_picker" name="dateLimite" value="<?php echo strftime("%d/%m/%Y", strtotime($dateLimiteStage)); ?>">
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 large-6 large-centered columns">
        <input class="large button expand" id="envoyer" name="submit" type="submit" value="Déposer le stage"/>
    </div>
</div>
<div class="row">
    <div class="small-12 large-6 large-centered columns text-center">
        <a class="button" href="./supprimerstage?id=<?php echo $_GET['id']; ?>" onclick="return confirm('Êtes-vous sur de vouloir supprimer définitivement ce stage?');">Supprimer ce stage</a>
    </div>
</div>


<?php include('all.footer.php'); ?>