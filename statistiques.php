<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin") {
    realDie();
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

    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_result($rechercheStage, $trouveStage, $totalOffres, $offresValide, $totalEtud, $totalEnt);
    $stmt->fetch();
    $stmt->close();
?>
    <div class="row panel">
        <div class="large-12 columns">
            <h5><b>Nombre total d'étudiants inscrits:</b> <?php echo $totalEtud; ?></h5>
            <h5><b>Nombre total d'entreprises inscrites:</b> <?php echo $totalEnt; ?></h5>
            <h5><b>Nombre total d'offres de stages:</b> <?php echo $totalOffres; ?></h5><br />
            <h5><b>Nombre d'étudiants qui n'ont pas encore trouvé de stages:</b> <?php echo $rechercheStage." (".round($rechercheStage*100/$totalEtud)."%)"; ?></h5>
            <h5><b>Nombre d'étudiants qui ont trouvé un stages:</b> <?php echo $trouveStage." (".round($trouveStage*100/$totalEtud)."%)" ?></h5>
            <h5><b>Nombre d'offre de stages toujours valides</b> <?php echo $offresValide." (".round($offresValide*100/$totalOffres)."%)" ?></h5>
        </div>
    </div>
<?php
}
include('all.footer.php');