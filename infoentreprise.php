<?php
include ('all.header.php');

if (!isset($_GET[id])) {
    header('Location: index');
    die();    
}

// Requète SQL
$query = "SELECT * FROM entreprises WHERE idEnt='$_GET[id]'";
// Exécution de la requète
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Remplissage du tableau
$data = mysqli_fetch_assoc($result);
?>
        <h1>Information sur l'entreprise <?php if ($_SESSION[type] == "admin") echo '<a href="majinfo?idEnt='.$_GET[id].'"><button class="float_r">Modifier l\'entreprise</button></a>'?></h1> 
        <div class="col_13 float_l">
            <img src="fichiers/profile/<?php echo md5($data[mailEnt]).".png" ?>" alt="Logo de l'entreprise" class="img_float_l img_frame" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"'/>
        </div>
        <div class="col_23 float_r">
            <h4>Nom</h4>
            <p><em><?php echo $data[nomEnt]; ?></em></p>
            <h4>Contact</h4>
            <p><strong>Nom du contact</strong>: <?php echo $data[prenomContactEnt]." ". $data[nomContactEnt];?></p>
            <p><strong>Téléphone</strong>: 
            <?php echo $data[telEnt];
                if($data[telSecEnt]!=NULL) {
                    echo " - ".$data[telSecEnt];
                }
            ?></p>
            <p><strong>E-mail</strong>: <?php echo $data[mailEnt]; if(($_SESSION[type] == "admin") || $_SESSION[type] == "etudiants") {
                echo ' - <a href="mail?ent='.$_GET[id].'">Contacter l\'entreprise</a></p>';
            } ?>
            <h4>Adresse</h4>
            <p><?php echo nl2br($data[adresseEnt]);?></p>
        </div>            
        <div class="cleaner h20"></div>
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU" type="text/javascript"></script>
        <div id="map" style="width: 820px; height: 320px"><br/></div> 
        <script type="text/javascript">
            //<![CDATA[
            function load() 
            {
                if (GBrowserIsCompatible()) {
                    var map = new GMap2(document.getElementById("map"));
                    map.addControl(new GSmallMapControl());
                    map.addControl(new GMapTypeControl());
                    var center = new GLatLng(<?php echo json_encode($data[latEnt]);?>, <?php echo json_encode($data[lngEnt]);?>);
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