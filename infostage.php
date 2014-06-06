<?php
include ('all.header.php');

if (!isset($_GET['id'])) {
    header('Location: index');
    die();    
}
$valideModif=false;
if ($_SESSION['type'] === "entreprises" || $_SESSION['type'] === "admin") {
    // Requète SQL
    $query = "SELECT COUNT(*) as 'existant' FROM stages WHERE idEnt='$_SESSION[idEnt]' AND idStage='$_GET[id]'";
    // Exécution de la requète pour les colones
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Affichage des colones
    $data = mysqli_fetch_assoc($result);
    if ($data['existant']==1 || $_SESSION['type'] == "admin") {
        $valideModif=true;
    }
}

// Requète SQL
$query = "SELECT * FROM stages,entreprises WHERE stages.idEnt=entreprises.idEnt AND idStage='$_GET[id]'";
// Exécution de la requète
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Remplissage du tableau
$data = mysqli_fetch_assoc($result);
?>
    	<h1>Information sur le stage <?php if ($valideModif) echo '<a href="majstage?id='.$_GET['id'].'"><button class="float_r">Modifier le stage</button></a>'?></h1>
        
        <div class="col_13 float_l">
            <img src="fichiers/profile/<?php echo md5($data['mailEnt']).".png" ?>" alt="Logo de l'entreprise" class="img_float_l img_frame" onerror='this.onerror = null; this.src="./fichiers/profile/opt.png"'/>
        </div>
        
        <div class="col_23 float_r">
            <h4>Nom</h4>
            <p><em><?php echo $data['nomStage'] ?></em></p>
            <h4>Sujet</h4>
            <p><?php echo $data['sujetStage'] ?></p>
            <h4>Niveau</h4>
            <p><?php echo $data['typeStage'] ?></p>
        </div>
        
		<div class="cleaner h10"></div>
        <div class="col_23 float_l">
            <h5>Détails</h5>
            <p><?php echo $data['detailsStage']; ?></p>
            <h5>Langages nécessaires</h5>
            <p><?php
                if ($data['htmlcssStage'] == 1) echo " HTML/CSS";
                if ($data['phpStage'] == 1) echo " PHP";
                if ($data['sqlStage'] == 1) echo " SQL";
                if ($data['javaStage'] == 1) echo " JAVA";
                if ($data['cStage'] == 1) echo " C";
                if ($data['csStage'] == 1) echo " C#";
            ?></p>
            <?php
                if ($data['langageAutreStage'] != NULL) {
                    echo '<h5>Autres langages</h5><p>'.$data['langageAutreStage'].'</p>';
                }
            ?>
            <h5>Contact</h5>
            <p><?php echo $data['prenomContactStage']." ". $data['nomContactStage']." - ".$data['mailContactStage']?></p>
            <p><?php echo "Téléphone : ".$data['telEnt'];
                if($data['telSecEnt']!=NULL) {
                    echo " ou ".$data['telSecEnt'];
                }
            ?></p>
            <p><?php echo nl2br($data['adresseEnt']); ?></p>
        </div>
        <div class="col_13 float_r">
            <h5>Date de début du stage</h5><span class="date"> <?php echo utf8_encode(strftime("%A %#d %B %Y",strtotime($data['dateDebutStage']))) ?></span>
            <div class="cleaner h10"></div>
            
            <h5>Date de fin du stage</h5><span class="date"> <?php echo utf8_encode(strftime("%A %#d %B %Y",strtotime($data['dateFinStage']))) ?></span>
            <div class="cleaner h10"></div>
            
            <h5>Durée du stage</h5><span class="date"> <?php echo $data['dureeStage']; ?> jours</span>
            <div class="cleaner h10"></div>
            
            <h5>Contacter avant le</h5><span class="date"> <?php echo utf8_encode(strftime("%A %#d %B %Y",strtotime($data['dateLimiteStage']))) ?></span>
            <div class="cleaner h10"></div>
            <?php if(($_SESSION['type'] == "admin") || $_SESSION['type'] == "etudiants") {
                echo '<p><a href="mail?stage='.$_GET['id'].'">Contacter l\'entreprise pour ce stage</a></p>';
            } ?>
            <div class="cleaner h10"></div>
            
            <h5>Lieu du stage</h5>
            <p><?php echo nl2br($data['lieuStage']); ?></p>
        </div>
        <div class="cleaner h10"></div>
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
        type="text/javascript"></script>
        <div id="map" style="width: 820px; height: 320px"><br/></div> 
        <script type="text/javascript">
            //<![CDATA[
            function load() 
            {
                if (GBrowserIsCompatible()) {
                    var map = new GMap2(document.getElementById("map"));
                    map.addControl(new GSmallMapControl());
                    map.addControl(new GMapTypeControl());
                    var center = new GLatLng(<?php echo json_encode($data['latStage']);?>, <?php echo json_encode($data['lngStage']);?>);
                    map.setCenter(center, 15);
                    var marker = new GMarker(center, {draggable: false});  
                    map.addOverlay(marker);           
                }
            } 
            load();
            //]]>
        </script>
<?php
include ('all.footer.php');
?>