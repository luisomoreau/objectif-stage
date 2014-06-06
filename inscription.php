<?php
if(!isset($_POST['typeinscription'])) {
    header('Location: index');
    die(); 
}
$loginreq=0;
include('all.header.php');
if($_POST['typeinscription'] == "etud") {
?>
<h1>Inscription étudiant</h1>
    <form action="inscriptiondb" method="POST" id="inscEtudiants" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEtud') && checkPass('mdpEtud','mdpEtud2'))">
        <input type="hidden" name="typeinscription" value="etud"/>	 
        <div class="col_23 float_l">			
    		<label for="mailEtud">Mail de l'unversité (Il sera votre identifiant)</label>
    		<input placeholder="prenom.nom" type="text" name="mailEtud" id="mailEtud" maxlength="100" required="required" value=""/> @etudiant.univ-nc.nc
        	<div class="cleaner h10"></div>
            
            <label for="mailPersoEtud">Mail personnel</label>
    		<input type="email" name="mailPersoEtud" id="mailPersoEtud" maxlength="100" required="required" value=""/>
    		<div class="cleaner h10"></div>
            
    		<label for="mdpEtud">Mot de passe (Un chiffre, une majuscule, une minuscule, minimum 6 caractères)</label>
    		<input type="password" name="mdpEtud" id="mdpEtud" maxlength="25" required="required" onkeyup="checkPatern('mdpEtud'); return false;"/>
            <span id="confirmPatern" class="confirmPatern"></span>
            <div class="cleaner h10"></div>
            
            <label for="mdpEtud2">Confirmer mot de passe</label>
    		<input type="password" name="mdpEtud2" id="mdpEtud2" maxlength="25" required="required" onkeyup="checkPass('mdpEtud','mdpEtud2'); return false;" />
            <span id="confirmMessage" class="confirmMessage"></span>
            <div class="cleaner h10"></div>
    
            <label for="prenomEtud">Prenom</label>
    		<input type="text" name="prenomEtud" id="prenomEtud" maxlength="50" required="required" value=""/>
            <div class="cleaner h10"></div>
            
            <label for="nomEtud">Nom</label>
    		<input type="text" name="nomEtud" id="nomEtud" maxlength="50" required="required" value="" />
    		<div class="cleaner h10"></div>
            
            <input type="radio" name="sexeEtud" id="sexeEtudF" value="F" required="required" />
            <label class="radio" for="sexeEtudF">Femme</label>
    		<input type="radio" name="sexeEtud" id="sexeEtudH" value="H" required="required" />
            <label class="radio" for="sexeEtudH" >Homme</label>
            
            <div class="cleaner h10"></div>
            <label for="telEtud">Téléphone </label>
    		<input placeholder="Principal" type="tel" maxlength="6" name="telEtud" id="telEtud" value="" onkeyup="verif_nombre(this)" required="required"/>
    		<input placeholder="Secondaire" type="tel" maxlength="6" name="telSecEtud" id="telSecEtud" value="" onkeyup="verif_nombre(this)" />
        </div>
        
        <div class="col_13 float_r">
            <label for="profilpic" >Ajouter une photo de profile<br />
                <img src="fichiers/profile/defaut.png" alt="Photo de profile" class="img_float_l img_frame" /> 
            </label>
            <input type="file" name="profilpic" id="profilpic"/>
            <div class="cleaner h10"></div>	
            
            <label>Date de naissance</label>
            <select name="naissanceJour" required>
            	<option selected="selected" disabled="disabled" value="">Jour</option>
                <?php
                for ($i=1;$i<=31;$i++) {
                    echo "<option value=".$i.">".$i."</option>";
                }
                ?>
            </select>
            <select name="naissanceMois" required>
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
            <select name="naissanceAnnee" required>
            	<option selected="selected" disabled="disabled" value="">Année</option>
                <?php 
                for ($i=2000;$i>=1940;$i--) {
                    echo "<option value=".$i.">".$i."</option>";
                }
                ?>
            </select>
            
            <label>Licence</label>
            <select name="licenceEtud" required>
                <option selected="selected" disabled="disabled" value="">Licence</option>
                <option>L2 SPI</option>
                <option>L3 SPI</option>
            </select>
            <div class="cleaner h10"></div>
        </div>
		<div class="cleaner h40"></div>
		<div class="centrer"><button id="envoyer" type="submit">S'inscrire</button></div>
    </form>
<?php
} else if($_POST['typeinscription'] == "ent") {
?>
<h1>Inscription entreprise</h1>
<form action="inscriptiondb" method="POST" id="inscEntreprise" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEnt') && checkPass('mdpEnt','mdpEnt2'))">
    <input type="hidden" name="typeinscription" value="ent" />	
    <div class="col_13 float_l">
        <label for="profilpic">Ajouter votre logo (facultatif)<br />
            <img src="fichiers/profile/defaut.png" alt="Logo de l'entreprise" class="img_float_l img_frame" />
        </label>
		<input class="float_l" type="file" name="profilpic" id="profilpic"/>
		<div class="cleaner h10"></div>	
        	
		<label for="nomEnt">Nom de l'entreprise</label>
		<input type="text" name="nomEnt" id="nomEnt" maxlength="100" required="required"/>
		<div class="cleaner h10"></div>
        
		<label for="mailEnt">Mail (Il sera votre identifiant)</label>
		<input type="email" name="mailEnt" id="mailEnt" maxlength="100" required="required"/>
        <div class="cleaner h10"></div>
        
        <label for="mdpEnt">Mot de passe (Un chiffre, une majuscule, une minuscule, minimum 6 caractères)</label>
		<input type="password" name="mdpEnt" id="mdpEnt" maxlength="25" required="required" onkeyup="checkPatern('mdpEnt'); return false;"/>
        <span id="confirmPatern" class="confirmPatern"></span>
        <div class="cleaner h10"></div>
        
        <label for="mdpEnt2">Confirmer mot de passe</label>
		<input type="password" name="mdpEnt2" id="mdpEnt2" maxlength="25" required="required" onkeyup="checkPass('mdpEnt','mdpEnt2'); return false;"/>
        <span id="confirmMessage" class="confirmMessage"></span>
        <div class="cleaner h10"></div>
        
        <label for="prenomContactEnt">Prenom de contact</label>
		<input type="text" name="prenomContactEnt" id="prenomContactEnt" maxlength="50" required="required"/>
        <div class="cleaner h10"></div>
        
        <label for="nomContactEnt">Nom de contact</label>
		<input type="text" name="nomContactEnt" id="nomContactEnt" maxlength="50" required="required"/>
		<div class="cleaner h10"></div>
    </div>
    <div class="col_23 float_r">
        <input id="lat" type="hidden" name="latEnt" value=""/>
        <input id="lng" type="hidden" name="lngEnt" value="" />          
        <label>Veuillez localiser votre entreprise :</label>
        <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU" type="text/javascript"></script>
        <div id="map" style="width: 500px; height: 320px;"><br/></div>
        <script type="text/javascript">
            //<![CDATA[
            loadmap(-22.2630844,166.4031372);
            //]]>
        </script>
        <div class="cleaner h10"></div>
        <label for="telEnt">Téléphone </label>
		<input placeholder="Principal" type="tel" maxlength="6" name="telEnt" id="telEnt" value="" onkeyup="verif_nombre(this)" required="required" />
		<input placeholder="Secondaire" type="tel" maxlength="6" name="telSecEnt" id="telSecEnt" value="" onkeyup="verif_nombre(this)" />
		<div class="cleaner h10"></div>
        
        <label for="adresseEnt">Adresse</label>
		<textarea name="adresseEnt" id="adresseEnt" maxlength="255" required="required"></textarea>
    </div>
    <div class="cleaner h40"></div>
	<div class="centrer"><button type="submit">S'inscrire</button></div>
</form>    
<?php
} else {
    header('Location: index');
    die();
}
include('all.footer.php');
?>