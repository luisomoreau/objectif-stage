<?php
include('nontraite.php');
include('all.header.php');
if ($_SESSION[type]!=="entreprises" && $_SESSION[type]!=="admin") {
    header('Location: /');
    die(); 
}    
// Requète SQL
$query = "SELECT * FROM Stages WHERE idStage='$_GET[id]'";

// Exécution de la requète
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));

$data = mysqli_fetch_assoc($result);

if ($data[idEnt]!==$_SESSION[idEnt] && $_SESSION[type] !== "admin") {
    header('Location: messtages');
    die(); 
}

// Affichage des resultats

$nomStage=$data[nomStage];
$prenomContactStage=$data[prenomContactStage];
$nomContactStage=$data[nomContactStage];
$mailContactStage=$data[mailContactStage];
$lieuStage=$data[lieuStage];
$latStage=$data[latStage];
$lngStage=$data[lngStage];

$dateDebutStage=$data[dateDebutStage];
$jourDebutStage=substr($dateDebutStage, -2);
$moisDebutStage=substr($dateDebutStage,-5,2);
$anneeDebutStage=substr($dateDebutStage,-10,4);

$dateFinStage=$data[dateFinStage];
$jourFinStage=substr($dateFinStage, -2);
$moisFinStage=substr($dateFinStage,-5,2);
$anneeFinStage=substr($dateFinStage,-10,4);

$dateLimiteStage=$data[dateLimiteStage];
$jourLimiteStage=substr($dateLimiteStage, -2);
$moisLimiteStage=substr($dateLimiteStage,-5,2);
$anneeLimiteStage=substr($dateLimiteStage,-10,4);

$typeStage=$data[typeStage];
$sujetStage=$data[sujetStage];
$detailsStage=$data[detailsStage];

$htmlcssStage=$data[htmlcssStage];
$phpStage=$data[phpStage];
$sqlStage=$data[sqlStage];
$javaStage=$data[javaStage];
$cStage=$data[cStage];
$csStage=$data[csStage];
$langageAutreStage=$data[langageAutreStage];
?>
        <h1>Modifier un stage</h1>
        <form action="majstagedb" method="POST" >
            <div class="col_13 float_l">	
                <?php if ($_SESSION[type] === "admin") echo '<input type="hidden" name="idEnt" value="'.$data[idEnt].'" />'; ?>	
                <label for="nomStage">Nom du stage</label>
            	<input type="text" name="nomStage" id="nomStage" maxlength="200" required="required" value="<?php echo $nomStage;?>"/>
            	<div class="cleaner h10"></div> 
                
                <label for="prenomContactStage">Prenom du contact pour le stage</label>
            	<input type="text" name="prenomContactStage" id="prenomContactStage" maxlength="50" required="required" value="<?php echo $prenomContactStage;?>"/>
            	<div class="cleaner h10"></div>
                
                <label for="nomContactStage">Nom du contact pour le stage</label>
            	<input type="text" name="nomContactStage" id="nomContactStage" maxlength="50" required="required" value="<?php echo $nomContactStage;?>"/>
            	<div class="cleaner h10"></div> 
                
                <label for="mailContactStage">Mail du contact pour le stage</label>
            	<input type="email" name="mailContactStage" id="mailContactStage" maxlength="100" required="required" value="<?php echo $mailContactStage;?>"/>
            	<div class="cleaner h10"></div>
                
                <label for="lieuStage">Lieu du stage</label>
            	<textarea name="lieuStage" id="lieuStage" maxlength="255" required="required"><?php echo $lieuStage;?></textarea>
            </div>
            <div class="col_23 float_r">
                <label for="map">Localisation du stage: </label>
                <input id="lat" type="hidden" name="latStage" value=""/>
                <input id="lng" type="hidden" name="lngStage" value="" />          
                <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU" type="text/javascript"></script>
                <div align="center" id="map" style="width: 500px; height: 320px"><br/></div>
                <script type="text/javascript">
                    //<![CDATA[
                    loadmap(<?php echo json_encode($data[latStage]);?>,<?php echo json_encode($data[lngStage]);?>);
                    //]]>
                </script>
            </div>
            <div class="cleaner h20"></div>
            <div class="col_23 float_l">
                <label for="sujetStage">Sujet du stage</label>
            	<textarea name="sujetStage" id="sujetStage" maxlength="1000" required="required"><?php echo $sujetStage;?></textarea>
            	<div class="cleaner h10"></div>
                
                <label for="detailsStage">Details du stage</label>
            	<textarea name="detailsStage" id="detailsStage" maxlength="1000" required="required"><?php echo $detailsStage;?></textarea>
            	<div class="cleaner h10"></div>
                
                Compétences necessaires :
                <div class="cleaner h10"></div>
                <input type="checkbox" name="htmlcssStage" id="htmlcssStage" value="1" <?php if($htmlcssStage==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="htmlcssStage" >HTML/CSS</label>
                
                <input type="hidden" name="idStage" value="<?php echo $_GET[id];?>"/>
                
                <input type="checkbox" name="phpStage" id="phpStage" value="1"<?php if($phpStage==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="phpStage" >PHP</label>
                
                <input type="checkbox" name="sqlStage" id="sqlStage" value="1"<?php if($sqlStage==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="sqlStage" >SQL</label>
                
                <input type="checkbox" name="javaStage" id="javaStage" value="1"<?php if($javaStage==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="javaStage" >JAVA</label>
                
                <input type="checkbox" name="cStage" id="cStage" value="1"<?php if($cStage==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="cStage" >C</label>
                
                <input type="checkbox" name="csStage" id="csStage" value="1"<?php if($csStage==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="csStage" >C#</label>
                <div class="cleaner h10"></div>
                
                <label for="langageAutreStage">Autres langages</label>
            	<textarea name="langageAutreStage" id="langageAutreStage" maxlength="255"><?php if($langageAutreStage!=="NULL") {echo $langageAutreStage;}?></textarea>
            </div>
            
            <div class="col_13 float_r">
                <label>Date début stage</label>
                <select name="dateJourDebutStage">
                    <?php
                        for ($i=1;$i<=31;$i++) {                    
                            if($jourDebutStage==$i) { 
                                echo "<option value=".$i.' selected="selected">'.$i."</option>";
                            } else {
                                echo "<option value=".$i.">".$i."</option>";
                            }          
                        }
                    ?>
                </select>
                <select name="dateMoisDebutStage">
                	<option value="1"<?php if($moisDebutStage==="01") { echo 'selected="selected"';}?>>Janvier</option>
                	<option value="2"<?php if($moisDebutStage==="02") { echo 'selected="selected"';}?>>Fevrier</option>
                	<option value="3"<?php if($moisDebutStage==="03") { echo 'selected="selected"';}?>>Mars</option>
                	<option value="4"<?php if($moisDebutStage==="04") { echo 'selected="selected"';}?>>Avril</option>
                	<option value="5"<?php if($moisDebutStage==="05") { echo 'selected="selected"';}?>>Mai</option>
                	<option value="6"<?php if($moisDebutStage==="06") { echo 'selected="selected"';}?>>Juin</option>
                	<option value="7"<?php if($moisDebutStage==="07") { echo 'selected="selected"';}?>>Juillet</option>
                	<option value="8"<?php if($moisDebutStage==="08") { echo 'selected="selected"';}?>>Août</option>
                	<option value="9"<?php if($moisDebutStage==="09") { echo 'selected="selected"';}?>>Septembre</option>
                	<option value="10"<?php if($moisDebutStage==="10") { echo 'selected="selected"';}?>>Octobre</option>
                	<option value="11"<?php if($moisDebutStage==="11") { echo 'selected="selected"';}?>>Novembre</option>
                	<option value="12"<?php if($moisDebutStage==="12") { echo 'selected="selected"';}?>>Décembre</option>
                </select>
                <select name="dateAnneeDebutStage">
                    <?php
                        for ($i=2010;$i<=2015;$i++) {           // choix des années          
                            if($anneeDebutStage==$i) { 
                                echo "<option value=".$i.' selected="selected">'.$i."</option>";
                            } else {
                                echo "<option value=".$i.">".$i."</option>";
                            }          
                        }
                    ?>
                </select>
                <div class="cleaner h10"></div>
                
                <label>Date fin stage</label>
                <select name="dateJourFinStage">
                    <?php
                        for ($i=1;$i<=31;$i++) {                    
                            if($jourFinStage==$i) { 
                                echo "<option value=".$i.' selected="selected">'.$i."</option>";
                            } else {
                                echo "<option value=".$i.">".$i."</option>";
                            }          
                        }
                    ?>
                </select>
                <select name="dateMoisFinStage">
                	<option value="1"<?php if($moisFinStage==="01") { echo 'selected="selected"';}?>>Janvier</option>
                	<option value="2"<?php if($moisFinStage==="02") { echo 'selected="selected"';}?>>Fevrier</option>
                	<option value="3"<?php if($moisFinStage==="03") { echo 'selected="selected"';}?>>Mars</option>
                	<option value="4"<?php if($moisFinStage==="04") { echo 'selected="selected"';}?>>Avril</option>
                	<option value="5"<?php if($moisFinStage==="05") { echo 'selected="selected"';}?>>Mai</option>
                	<option value="6"<?php if($moisFinStage==="06") { echo 'selected="selected"';}?>>Juin</option>
                	<option value="7"<?php if($moisFinStage==="07") { echo 'selected="selected"';}?>>Juillet</option>
                	<option value="8"<?php if($moisFinStage==="08") { echo 'selected="selected"';}?>>Août</option>
                	<option value="9"<?php if($moisFinStage==="09") { echo 'selected="selected"';}?>>Septembre</option>
                	<option value="10"<?php if($moisFinStage==="10") { echo 'selected="selected"';}?>>Octobre</option>
                	<option value="11"<?php if($moisFinStage==="11") { echo 'selected="selected"';}?>>Novembre</option>
                	<option value="12"<?php if($moisFinStage==="12") { echo 'selected="selected"';}?>>Décembre</option>
                </select>
                <select name="dateAnneeFinStage">
                    <?php
                        for ($i=2010;$i<=2015;$i++) {           // choix des années          
                            if($anneeFinStage==$i) { 
                                echo "<option value=".$i.' selected="selected">'.$i."</option>";
                            } else {
                                echo "<option value=".$i.">".$i."</option>";
                            }          
                        }
                    ?>
                </select>
                <div class="cleaner h10"></div>
                
                <label>Date expiration offre de stage</label>
                <select name="dateJourLimiteStage">
                    <?php
                        for ($i=1;$i<=31;$i++) {                    
                            if($jourLimiteStage==$i) { 
                                echo "<option value=".$i.' selected="selected">'.$i."</option>";
                            } else {
                                echo "<option value=".$i.">".$i."</option>";
                            }          
                        }
                    ?>
                </select>
                <select name="dateMoisLimiteStage">
                	<option value="1"<?php if($moisLimiteStage==="01") { echo 'selected="selected"';}?>>Janvier</option>
                	<option value="2"<?php if($moisLimiteStage==="02") { echo 'selected="selected"';}?>>Fevrier</option>
                	<option value="3"<?php if($moisLimiteStage==="03") { echo 'selected="selected"';}?>>Mars</option>
                	<option value="4"<?php if($moisLimiteStage==="04") { echo 'selected="selected"';}?>>Avril</option>
                	<option value="5"<?php if($moisLimiteStage==="05") { echo 'selected="selected"';}?>>Mai</option>
                	<option value="6"<?php if($moisLimiteStage==="06") { echo 'selected="selected"';}?>>Juin</option>
                	<option value="7"<?php if($moisLimiteStage==="07") { echo 'selected="selected"';}?>>Juillet</option>
                	<option value="8"<?php if($moisLimiteStage==="08") { echo 'selected="selected"';}?>>Août</option>
                	<option value="9"<?php if($moisLimiteStage==="09") { echo 'selected="selected"';}?>>Septembre</option>
                	<option value="10"<?php if($moisLimiteStage==="10") { echo 'selected="selected"';}?>>Octobre</option>
                	<option value="11"<?php if($moisLimiteStage==="11") { echo 'selected="selected"';}?>>Novembre</option>
                	<option value="12"<?php if($moisLimiteStage==="12") { echo 'selected="selected"';}?>>Décembre</option>
                </select>
                <select name="dateAnneeLimiteStage">
                    <?php
                        for ($i=2010;$i<=2015;$i++) {           // choix des années          
                            if($anneeLimiteStage==$i) { 
                                echo "<option value=".$i.' selected="selected">'.$i."</option>";
                            } else {
                                echo "<option value=".$i.">".$i."</option>";
                            }          
                        }
                    ?>
                </select>
                <div class="cleaner h10"></div>
                
                <label for="typeStage">Type</label>
                <select name="typeStage">
                    <option <?php if($typeStage==="Stage L2") { echo 'selected="selected"';}?>>Stage L2</option>
                    <option <?php if($typeStage==="Stage L3") { echo 'selected="selected"';}?>>Stage L3</option>
                    <option <?php if($typeStage==="Projet Tuteuré") { echo 'selected="selected"';}?>>Projet Tuteuré</option>
                </select>
            </div>
        	<div class="cleaner h20"></div>
            <button class="float_l" type="submit">Modifier le stage</button>
        </form>
         
        <form action="supprimerstage" method="POST" id="recherche">
            <?php if ($_SESSION[type] === "admin") echo '<input type="hidden" name="idEnt" value="'.$data[idEnt].'" />'; ?>
            <input type="hidden" name="idStage" value="<?php echo $_GET[id];?>"/>
            <button class="float_r" type="submit" onclick="return confirm('Êtes-vous sur de vouloir supprimer définitivement ce stage?');">Supprimer le stage</button>
        </form>
<?php include('all.footer.php'); ?>