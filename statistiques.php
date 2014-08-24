<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin") {
    realDie();
} else {
    if (isset($_GET['filiere']) && $_GET['filiere'] != '1000000') {
        $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
        if (!($stmt = $mysqli->prepare("SELECT
        (SELECT COUNT(idEtud) FROM etudiants WHERE trouveStageEtud = 0 AND filiereEtud = ?) AS 'rechercheStage',
        (SELECT COUNT(idEtud) FROM etudiants WHERE trouveStageEtud = 1 AND filiereEtud = ?) AS 'trouveStage',
        (SELECT COUNT(idStage) FROM stages WHERE filiereStage = ?) AS 'totalOffres',
        (SELECT COUNT(idStage) FROM stages WHERE TO_DAYS(NOW()) < TO_DAYS(dateLimiteStage) AND filiereStage = ?) AS 'offresValide' ,
        (SELECT COUNT(idEtud) FROM etudiants WHERE filiereEtud = ?) AS 'totalEtud',
        (SELECT COUNT(idEnt) FROM entreprises) AS 'totalEnt'
        ;"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        $stmt->bind_param('sssss',$_GET['filiere'],$_GET['filiere'],$_GET['filiere'],$_GET['filiere'],$_GET['filiere']);
    } else {
        $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
        if (!($stmt = $mysqli->prepare("SELECT
        (SELECT COUNT(idEtud) FROM etudiants WHERE trouveStageEtud = 0) AS 'rechercheStage',
        (SELECT COUNT(idEtud) FROM etudiants WHERE trouveStageEtud = 1) AS 'trouveStage',
        (SELECT COUNT(idStage) FROM stages) AS 'totalOffres',
        (SELECT COUNT(idStage) FROM stages WHERE TO_DAYS(NOW()) < TO_DAYS(dateLimiteStage)) AS 'offresValide',
        (SELECT COUNT(idEtud) FROM etudiants) AS 'totalEtud',
        (SELECT COUNT(idEnt) FROM entreprises) AS 'totalEnt';"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }

    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_result($rechercheStage, $trouveStage, $totalOffres, $offresValide, $totalEtud, $totalEnt);
    $stmt->fetch();
    $stmt->close();

?>
    <div class="row">
        <div class="panel row">
            <div class="large-12 columns">
                <div class="row collapse">
                    <div class="large-3 columns">
                        <span class="prefix">Filière</span>
                    </div>
                    <div class="large-9 columns">
                        <form method="GET">
                            <select name="filiere" onchange="this.form.submit()">
                                <option value="1000000">Toute filière</option>
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
                                    echo ' <option value="' . $diplome_sise . '"';
                                    if (isset($_GET['filiere']) && $_GET['filiere'] == $diplome_sise) echo "selected";
                                    echo '>' . utf8_encode($diplome_nom) . '</option>';
                                }
                                $stmt->close();
                                ?>
                            </select>
                            <noscript><input type="submit" value="Submit"></noscript>
                        </form>
                    </div>
                </div>
                <br>
                <h5><b>Nombre total d'étudiants inscrits:</b> <?php echo $totalEtud; ?></h5>
                <h5><b>Nombre total d'entreprises inscrites:</b> <?php echo $totalEnt; ?></h5>
                <h5><b>Nombre total d'offres de stages:</b> <?php echo $totalOffres; ?></h5><br>
                <h5><b>Nombre d'étudiants qui n'ont pas encore trouvé de stages:</b> <?php echo $rechercheStage." (".round($rechercheStage*100/$totalEtud)."%)"; ?></h5>
                <h5><b>Nombre d'étudiants qui ont trouvé un stages:</b> <?php echo $trouveStage." (".round($trouveStage*100/$totalEtud)."%)" ?></h5>
                <h5><b>Nombre d'offre de stages toujours valides</b> <?php
                    if ($totalOffres!=0) echo $offresValide." (".round($offresValide*100/$totalOffres)."%)"; else echo '0 (0%)';?></h5>
            </div>
        </div>
    </div>
<?php
}
include('all.footer.php');