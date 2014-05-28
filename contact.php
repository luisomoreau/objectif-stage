<?php 
$loginreq=0;
include('all.header.php');

if (isset($_POST[nomContact]) && isset($_POST[mailContact]) && isset($_POST[sujetContact]) && isset($_POST[messageContact]) ) {
    
    foreach($_POST as $col=>$val) {
            if(empty($val)) {
                $$col = "";
            } else {
                $$col = str_replace("'","\'",$val);
            }
    } 
    
    $ip=$_SERVER['REMOTE_ADDR'];
    
    $query = "SELECT idEtud FROM Etudiants WHERE mailEtud ='$_SESSION[identifiant]'"; 
    $result = mysqli_query($dblink, $query); 
    $data = mysqli_fetch_assoc($result);
    
    $query2 = "SELECT idEtud FROM Etudiants WHERE mailEtud ='$_SESSION[identifiant]'"; 
    $result2 = mysqli_query($dblink, $query2); 
    $data2 = mysqli_fetch_assoc($result2);
    
    $idEtud=(int)$data[idEtud];
    $idEnt=(int)$data2[idEnt];
    
    // Requète SQL
    $query3 = "INSERT INTO Contact (nomContact, mailContact, sujetContact, messageContact, IP, idEtud, idEnt)
                        VALUES ('$nomContact', '$mailContact', '$sujetContact', '$messageContact', '$ip', '$idEtud', '$idEnt')"; 
    // Exécution de la requète
    $result3 = mysqli_query($dblink, $query3); 
    if (!$result) {
        echo "Erreur lors de l'envoie du message:".mysqli_error($dblink);
        include('all.footer.php');
        die();
    }
    include('all.footer.php');
 ?>
        <script> 
            alert('Le message a bien \351t\351 envoy\351 !');
            window.location = "/";
         </script>
<?php
}
?>
            <h1>Coordonnées de contact</h1>
        	<p>Voici nos coordonnées. Si vous avez une question, une remarque ou un problème, n'hésitez pas à nous contacter via le formulaire de contact. Un e-mail sera envoyé automatiquement à l'administrateur.</p>
            <div class="cleaner h30"></div>
            <div class="col_12 float_l">
            <h4>Contactez-nous</h4>
            <form method="post" name="contact" action="contact">
                <label for="nomContact">Nom:</label>
                <input type="text" id="nomContact" name="nomContact" maxlength="50" required="required" value="<?php echo $nom_disp." ".$nom_disp2; ?>" />
                <div class="cleaner h10"></div>
                
                <label for="mailContact">E-mail:</label>
                <input type="email" id="mailContact" name="mailContact" maxlength="100" required="required" value="<?php echo $_SESSION[identifiant]; ?>" />
                <div class="cleaner h10"></div>
				
				<label for="sujetContact">Sujet:</label>
                <input type="text" name="sujetContact" id="sujetContact" maxlength="150" required="required" />
                <div class="cleaner h10"></div>
				
                <label for="messageContact">Message:</label>
                <textarea id="messageContact" name="messageContact" maxlength="150" required="required"></textarea>
                <div class="cleaner h10"></div>
                
                <button type="submit" >Envoyer à l'administrateur</button>        
            </form>
                
            </div> 
             <div class="col_12 float_r">                    
                <h4>Addresse</h4>
                <h6>Université de la Nouvelle-Calédonie</h6>
                145, Avenue C.R.15<br />
                Nouville - 98800 Nouméa
                <div class="cleaner h30"></div>
                <h4>Boite postale</h4>
                Campus de Nouville,<br />
                BP R4<br />
                98851 Nouméa CEDEX
                <div class="cleaner h30"></div>
                <h4>Contact</h4>
				<strong>Téléphone:</strong> 29 02 90 <br />
                <strong>Fax:</strong> 254829 <br />
                <strong>E-mail:</strong> <a href="mailto:webmestre@univ-nc.nc">webmestre@univ-nc.nc</a><br />
                <strong>Site web:</strong> <a href="http://www.univ-nc.nc">www.univ-nc.nc</a>
            </div>
            <div class="cleaner h30"></div>
            <div class="col">     
            <h4>Notre localisation</h4>
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
                            var center = new GLatLng(-22.2630844,166.4031372);
                            map.setCenter(center, 15);
                            var marker = new GMarker(center, {draggable: false});  
                            map.addOverlay(marker);           
                        }
                    } 
                    load();
                    //]]>
                </script>
            </div>
            <div class="cleaner"></div>
<?php
    include('all.footer.php');
?>