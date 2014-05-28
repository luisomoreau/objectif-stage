<?php 
include('all.header.php');
if ($_SESSION[type]!=="entreprises") {
    header('Location: index');
    die(); 
}
        // Connection SQL
        $dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));
               
        // Requète SQL
        $query = "SELECT * FROM Entreprises WHERE idEnt=$_SESSION[idEnt]";
                
        // Exécution de la requète
        $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
        
        $data = mysqli_fetch_assoc($result);

?>
<h1>Depôt stage</h1>
<form action="depotstagedb" method="POST" id="depoStage">
    <div class="col_1 float_l">	
        <label for="nomStage">Nom stage</label>
    	<input type="text" name="nomStage" id="nomStage" maxlength="200" required="required" value=""/>
    	<div class="cleaner h10"></div> 
        
        <label for="prenomContactStage">Prenom du contact pour le stage</label>
    	<input type="text" name="prenomContactStage" id="prenomContactStage" maxlength="50" required="required" value="<?php echo $data[prenomContactEnt];?>"/>
    	<div class="cleaner h10"></div>
        
        <label for="nomContactStage">Nom du contact pour le stage</label>
    	<input type="text" name="nomContactStage" id="nomContactStage" maxlength="50" required="required" value="<?php echo $data[nomContactEnt];?>"/>
    	<div class="cleaner h10"></div> 
        
        <label for="mailContactStage">Mail du contact pour le stage</label>
    	<input type="email" name="mailContactStage" id="mailContactStage" maxlength="100" required="required" value="<?php echo $data[mailEnt];?>"/>
    	<div class="cleaner h10"></div>
        
        <label for="lieuStage">Lieu du stage</label>
    	<textarea name="lieuStage" id="lieuStage" maxlength="255" required="required"><?php echo $data[adresseEnt];?></textarea>
    	<div class="cleaner h10"></div>
    </div>
    <div class="col_23 float_r">
        <label>Veuillez indiquer le lieu du stage : </label>       
        <input id="lat" type="hidden" name="latStage" value=""/>
        <input id="lng" type="hidden" name="lngStage" value="" />
           
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
        type="text/javascript"></script>
        <div id="map" style="width: 500px; height: 330px"><br/></div>
        <script type="text/javascript">
            //<![CDATA[
            loadmap(<?php echo $data[latEnt].",".$data[lngEnt]?>);
            //]]>
        </script>
    </div>
    <div class="cleaner h10"></div>
    <div class="col_23 float_l">
        <label for="sujetStage">Sujet du stage</label>
    	<textarea name="sujetStage" id="sujetStage" maxlength="1000" required="required"></textarea>
    	<div class="cleaner h10"></div>
        
        <label for="detailsStage">Details du stage</label>
    	<textarea name="detailsStage" id="detailsStage" maxlength="1000" required="required"></textarea>
    	<div class="cleaner h10"></div>
        
        Compétences necessaires :
        <div class="cleaner h10"></div>
        <input type="checkbox" name="htmlcssStage" id="htmlcssStage" value="1"/>
        <label class="checkbox" for="htmlcssStage" >HTML/CSS</label>
        
        <input type="checkbox" name="phpStage" id="phpStage" value="1"/>
        <label class="checkbox" for="phpStage" >PHP</label>
        
        <input type="checkbox" name="sqlStage" id="sqlStage" value="1"/>
        <label class="checkbox" for="sqlStage" >SQL</label>
        
        <input type="checkbox" name="javaStage" id="javaStage" value="1"/>
        <label class="checkbox" for="javaStage">JAVA</label>
        
        <input type="checkbox" name="cStage" id="cStage" value="1"/>
        <label class="checkbox" for="cStage">C</label>
        
        <input type="checkbox" name="csStage" id="csStage" value="1"/>
        <label class="checkbox" for="csStage">C#</label>
        <div class="cleaner h10"></div>
        
        <label for="langageAutreStage">Autres compétences</label>
    	<textarea name="langageAutreStage" id="langageAutreStage" maxlength="255"></textarea>
    	<div class="cleaner h10"></div>
        
        <label>Stage rémunéré :</label>
        <input type="radio" name="remuStage" id="remuStageO" value="1" required="required" />
        <label class="radio" for="remuStageO" >Oui</label>
    	<input type="radio" name="remuStage" id="remuStageN" value="0" required="required" />
        <label class="radio" for="remuStageN" >Non</label>
    </div>
    <div class="col_13 float_r">
        <div class="cleaner h10"></div>
        <label>Niveau du stage</label>
        <select name="typeStage" required>
            <option selected="selected" disabled="disabled" value="">Niveau</option>
            <option>Stage L2</option>
            <option>Stage L3</option>
            <option>Projet Tuteuré</option>
        </select>
        <div class="cleaner h10"></div>
        
        <label>Date début stage</label>
        <select name="dateJourDebutStage" required>
        	<option selected="selected" disabled="disabled" value="">Jour</option>
            <?php
            for ($i=1;$i<=31;$i++) {
                echo "<option value=".$i.">".$i."</option>";
            }
            ?>
        </select>
        <select name="dateMoisDebutStage" required>
        	<option selected="selected" disabled="disabled" value="">Mois</option>
        	<option value="1">Janvier</option>
        	<option value="2">Fevrier</option>
        	<option value="3">Mars</option>
        	<option value="4">Avril</option>
        	<option value="5">Mai</option>
        	<option value="6">Juin</option>
        	<option value="7">Juillet</option>
        	<option value="8">Août</option>
        	<option value="9">Septembre</option>
        	<option value="10">Octobre</option>
        	<option value="11">Novembre</option>
        	<option value="12">Décembre</option>
        </select>
        <select name="dateAnneeDebutStage" required>
        	<option selected="selected" disabled="disabled" value="">Année</option>
            <option value="2015">2015</option>
        	<option value="2014">2014</option>
        	<option value="2013">2013</option>
        </select>
        <div class="cleaner h10"></div>
        
        <label>Date fin stage</label>
        <select name="dateJourFinStage" required>
        	<option selected="selected" disabled="disabled" value="">Jour</option>
            <?php
            for ($i=1;$i<=31;$i++) {
                echo "<option value=".$i.">".$i."</option>";
            }
            ?>
        </select>
        <select name="dateMoisFinStage" required>
        	<option selected="selected" disabled="disabled" value="">Mois</option>
        	<option value="1">Janvier</option>
        	<option value="2">Fevrier</option>
        	<option value="3">Mars</option>
        	<option value="4">Avril</option>
        	<option value="5">Mai</option>
        	<option value="6">Juin</option>
        	<option value="7">Juillet</option>
        	<option value="8">Août</option>
        	<option value="9">Septembre</option>
        	<option value="10">Octobre</option>
        	<option value="11">Novembre</option>
        	<option value="12">Décembre</option>
        </select>
        <select name="dateAnneeFinStage" required>
        	<option selected="selected" disabled="disabled" value="">Année</option>
            <option value="2015">2015</option>
        	<option value="2014">2014</option>
        	<option value="2013">2013</option>
        </select>
        <div class="cleaner h10"></div>
        
        <label>Date expiration offre de stage</label>
        <select name="dateJourLimiteStage" required>
        	<option selected="selected" disabled="disabled" value="">Jour</option>
            <?php
            for ($i=1;$i<=31;$i++) {
                echo "<option value=".$i.">".$i."</option>";
            }
            ?>
        </select>
        <select name="dateMoisLimiteStage" required>
        	<option selected="selected" disabled="disabled" value="">Mois</option>
        	<option value="1">Janvier</option>
        	<option value="2">Fevrier</option>
        	<option value="3">Mars</option>
        	<option value="4">Avril</option>
        	<option value="5">Mai</option>
        	<option value="6">Juin</option>
        	<option value="7">Juillet</option>
        	<option value="8">Août</option>
        	<option value="9">Septembre</option>
        	<option value="10">Octobre</option>
        	<option value="11">Novembre</option>
        	<option value="12">Décembre</option>
        </select>
        <select name="dateAnneeLimiteStage" required>
        	<option selected="selected" disabled="disabled" value="">Année</option>
            <option value="2015">2015</option>
        	<option value="2014">2014</option>
        	<option value="2013">2013</option>
        </select>
    </div>
    <div class="cleaner h20"></div>
    <button class="float_l" type="submit">Deposer le stage</button>
</form>
<?php include('all.footer.php'); ?>