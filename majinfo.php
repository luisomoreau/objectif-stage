<?php
    include('all.header.php');
    $mysqli = new mysqli($sqlserver,$sqlid,$sqlpwd,$sqldb);
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date dans le passé
    if ($_SESSION['connected'] === "etud" || (isset($_GET['idEtud']) && $_SESSION['type'] === "admin")) {
        // Requète SQL
        if ($_SESSION['connected'] === "admin") {
            //$query = "SELECT * FROM Etudiants WHERE idEtud='$_GET[idEtud]'";
            if (!($stmt = $mysqli->prepare('SELECT * FROM Etudiants WHERE idEtud=?'))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            $stmt->bind_param('i', $_GET['idEtud']);
        } else {
            //$query = "SELECT * FROM Etudiants WHERE mailEtud='$_SESSION[identifiant]'";
            if (!($stmt = $mysqli->prepare('SELECT mailEtud, mailPersoEtud, nomEtud, prenomEtud, trouveStageEtud, licenceEtud,
                                                    sexeEtud, naissanceEtud, telEtud, telSecEtud
                                            FROM Etudiants
                                            WHERE mailEtud="nicolas.brengard@etudiant.univ-nc.nc"'))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            //$stmt->bind_param('i', $_SESSION['identifiant']);
        }
        $stmt->execute();
        $stmt->bind_result($mailEtud, $mailPersoEtud, $nomEtud, $prenomEtud, $trouveStageEtud, $licenceEtud, $sexeEtud, $naissanceEtud, $telEtud, $telSecEtud);
        $stmt->fetch();
        $stmt->close();
        $jourEtud = date('d', strtotime($naissanceEtud));
        $moisEtud=date('m', strtotime($naissanceEtud));
        $anneeEtud=date('Y', strtotime($naissanceEtud));
        echo '<section class="row">
            <div class="small-12 columns">
                <h1>Profil Etudiant';
        if ($_SESSION['connected'] === "admin") echo '<a href="supprimercompte?idEtud=' . $_GET['idEtud'] . '"><button class="float_r" onclick="return confirm(\'Êtes-vous sur de vouloir supprimer définitivement ce compte?\');">Supprimer le compte</button></a>';
        echo '</h1>
            </div>
         </section>';
        ?>
    <form action="majinfodb" method="POST" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEtud') && checkPass('mdpEtud','mdpEtud2'))"> 
        <div class="row">
            <div class="large-8 columns">
                <?php if ($_SESSION['connected'] === "admin") echo '<input type="hidden" name="mailEtud" value="'.$mailEtud.'" />'; ?>
                <label for="mailEtud">Identifiant (Non modifiable)</label>
                <input placeholder="<?php echo $mailEtud;?>" type="text" name="mailEtud" id="mailEtud" maxlength="100" required="required" disabled="disabled"/>
                <div class="cleaner h10"></div>

                <label for="mailPersoEtud">Mail personnel</label>
                <input type="email" name="mailPersoEtud" id="mailPersoEtud" maxlength="100" required="required" value="<?php echo $mailPersoEtud;?>"/>
                <div class="cleaner h10"></div>

                <label for="mdpEtud">Mot de passe (Un chiffre, une majuscule, une minuscule, minimum 6 caractères)</label>
                <input type="password" name="mdpEtud" id="mdpEtud" maxlength="25" required="required" onkeyup="checkPatern('mdpEtud'); return false;" value="Defaut123"/>
                <span id="confirmPatern" class="confirmPatern"></span>
                <div class="cleaner h10"></div>

                <label for="mdpEtud2">Confirmer mot de passe</label>
                <input type="password" name="mdpEtud2" id="mdpEtud2" maxlength="25" required="required" onkeyup="checkPass('mdpEtud','mdpEtud2'); return false;" value="Defaut123"/>
                <span id="confirmMessage" class="confirmMessage"></span>
                <div class="cleaner h10"></div>

                <label for="prenomEtud">Prenom</label>
                <input type="text" name="prenomEtud" id="prenomEtud" maxlength="50" required="required" value="<?php echo $prenomEtud;?>"/>
                <div class="cleaner h10"></div>

                <label for="nomEtud">Nom</label>
                <input type="text" name="nomEtud" id="nomEtud" maxlength="50" required="required" value="<?php echo $nomEtud;?>" />
                <div class="cleaner h10"></div>

                <input type="radio" name="sexeEtud" id="sexeEtudF" value="F" required="required" <?php if($sexeEtud==="F") { echo 'checked="checked"';}?>  />
                <label class="radio" for="sexeEtudF">Femme</label>
                <input type="radio" name="sexeEtud" id="sexeEtudH" value="H" required="required" <?php if($sexeEtud==="H") { echo 'checked="checked"';}?> />
                <label class="radio" for="sexeEtudH" >Homme</label>

                <div class="cleaner h10"></div>
                <label for="telEtud">Téléphone </label>
                <input placeholder="Principal" type="tel" maxlength="6" name="telEtud" id="telEtud" onkeyup="verif_nombre(this)" required="required" value="<?php echo $telEtud;?>"/>
                <input placeholder="Secondaire" type="tel" maxlength="6" name="telSecEtud" id="telSecEtud" onkeyup="verif_nombre(this)" value="<?php echo $telSecEtud;?>"/>
                <div class="cleaner h10"></div>
            </div>
            <div class="large-4 columns">
                <label for="profilpic" >Modifier ma photo de profile - <a href="javascript:window.location.reload()">Actualiser</a><br />
                    <img src="fichiers/profile/<?php echo md5($mailEtud).".png" ?>" alt="Photo de profile" class="img_float_l img_frame" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"' />
                </label>
                <input type="file" name="profilpic" id="profilpic"/>
                <div class="cleaner h10"></div>

                <label>Date de naissance</label>
                <select name="naissanceJour" required>
                    <option disabled="disabled" value="">Jour</option>
                    <option value="1" <?php if($jourEtud==="01") { echo 'selected="selected"';}?>>1</option>
                    <option value="2" <?php if($jourEtud==="02") { echo 'selected="selected"';}?>>2</option>
                    <option value="3" <?php if($jourEtud==="03") { echo 'selected="selected"';}?>>3</option>
                    <option value="4" <?php if($jourEtud==="04") { echo 'selected="selected"';}?>>4</option>
                    <option value="5" <?php if($jourEtud==="05") { echo 'selected="selected"';}?>>5</option>
                    <option value="6" <?php if($jourEtud==="06") { echo 'selected="selected"';}?>>6</option>
                    <option value="7" <?php if($jourEtud==="07") { echo 'selected="selected"';}?>>7</option>
                    <option value="8" <?php if($jourEtud==="08") { echo 'selected="selected"';}?>>8</option>
                    <option value="9" <?php if($jourEtud==="09") { echo 'selected="selected"';}?>>9</option>
                    <option value="10" <?php if($jourEtud==="10") { echo 'selected="selected"';}?>>10</option>
                    <option value="11" <?php if($jourEtud==="11") { echo 'selected="selected"';}?>>11</option>
                    <option value="12" <?php if($jourEtud==="12") { echo 'selected="selected"';}?>>12</option>
                    <option value="13" <?php if($jourEtud==="13") { echo 'selected="selected"';}?>>13</option>
                    <option value="14" <?php if($jourEtud==="14") { echo 'selected="selected"';}?>>14</option>
                    <option value="15" <?php if($jourEtud==="15") { echo 'selected="selected"';}?>>15</option>
                    <option value="16" <?php if($jourEtud==="16") { echo 'selected="selected"';}?>>16</option>
                    <option value="17" <?php if($jourEtud==="17") { echo 'selected="selected"';}?>>17</option>
                    <option value="18" <?php if($jourEtud==="18") { echo 'selected="selected"';}?>>18</option>
                    <option value="19" <?php if($jourEtud==="19") { echo 'selected="selected"';}?>>19</option>
                    <option value="20" <?php if($jourEtud==="20") { echo 'selected="selected"';}?>>20</option>
                    <option value="21" <?php if($jourEtud==="21") { echo 'selected="selected"';}?>>21</option>
                    <option value="22" <?php if($jourEtud==="22") { echo 'selected="selected"';}?>>22</option>
                    <option value="23" <?php if($jourEtud==="23") { echo 'selected="selected"';}?>>23</option>
                    <option value="24" <?php if($jourEtud==="24") { echo 'selected="selected"';}?>>24</option>
                    <option value="25" <?php if($jourEtud==="25") { echo 'selected="selected"';}?>>25</option>
                    <option value="26" <?php if($jourEtud==="26") { echo 'selected="selected"';}?>>26</option>
                    <option value="27" <?php if($jourEtud==="27") { echo 'selected="selected"';}?>>27</option>
                    <option value="28" <?php if($jourEtud==="28") { echo 'selected="selected"';}?>>28</option>
                    <option value="29" <?php if($jourEtud==="29") { echo 'selected="selected"';}?>>29</option>
                    <option value="30" <?php if($jourEtud==="30") { echo 'selected="selected"';}?>>30</option>
                    <option value="31" <?php if($jourEtud==="31") { echo 'selected="selected"';}?>>31</option>
                </select>
                <select name="naissanceMois" required>
                    <option disabled="disabled" value="">Mois</option>
                    <option value="1" <?php if($moisEtud==="01") { echo 'selected="selected"';}?>>Janvier</option>
                    <option value="2" <?php if($moisEtud==="02") { echo 'selected="selected"';}?>>Fevrier</option>
                    <option value="3" <?php if($moisEtud==="03") { echo 'selected="selected"';}?>>Mars</option>
                    <option value="4" <?php if($moisEtud==="04") { echo 'selected="selected"';}?>>Avril</option>
                    <option value="5" <?php if($moisEtud==="05") { echo 'selected="selected"';}?>>Mai</option>
                    <option value="6" <?php if($moisEtud==="06") { echo 'selected="selected"';}?>>Juin</option>
                    <option value="7" <?php if($moisEtud==="07") { echo 'selected="selected"';}?>>Juillet</option>
                    <option value="8" <?php if($moisEtud==="08") { echo 'selected="selected"';}?>>Août</option>
                    <option value="9" <?php if($moisEtud==="09") { echo 'selected="selected"';}?>>Septembre</option>
                    <option value="10" <?php if($moisEtud==="10") { echo 'selected="selected"';}?>>Octobre</option>
                    <option value="11" <?php if($moisEtud==="11") { echo 'selected="selected"';}?>>Novembre</option>
                    <option value="12" <?php if($moisEtud==="12") { echo 'selected="selected"';}?>>Décembre</option>
                </select>
                <select name="naissanceAnnee" required>
                    <option disabled="disabled" value="">Année</option>
                    <option value="2004" <?php if($anneeEtud==="2004") { echo 'selected="selected"';}?>>2004</option>
                    <option value="2003" <?php if($anneeEtud==="2003") { echo 'selected="selected"';}?>>2003</option>
                    <option value="2002" <?php if($anneeEtud==="2002") { echo 'selected="selected"';}?>>2002</option>
                    <option value="2001" <?php if($anneeEtud==="2001") { echo 'selected="selected"';}?>>2001</option>
                    <option value="2000" <?php if($anneeEtud==="2000") { echo 'selected="selected"';}?>>2000</option>
                    <option value="1999" <?php if($anneeEtud==="1999") { echo 'selected="selected"';}?>>1999</option>
                    <option value="1998" <?php if($anneeEtud==="1998") { echo 'selected="selected"';}?>>1998</option>
                    <option value="1997" <?php if($anneeEtud==="1997") { echo 'selected="selected"';}?>>1997</option>
                    <option value="1996" <?php if($anneeEtud==="1996") { echo 'selected="selected"';}?>>1996</option>
                    <option value="1995" <?php if($anneeEtud==="1995") { echo 'selected="selected"';}?>>1995</option>
                    <option value="1994" <?php if($anneeEtud==="1994") { echo 'selected="selected"';}?>>1994</option>
                    <option value="1993" <?php if($anneeEtud==="1993") { echo 'selected="selected"';}?>>1993</option>
                    <option value="1992" <?php if($anneeEtud==="1992") { echo 'selected="selected"';}?>>1992</option>
                    <option value="1991" <?php if($anneeEtud==="1991") { echo 'selected="selected"';}?>>1991</option>
                    <option value="1990" <?php if($anneeEtud==="1990") { echo 'selected="selected"';}?>>1990</option>
                    <option value="1989" <?php if($anneeEtud==="1989") { echo 'selected="selected"';}?>>1989</option>
                    <option value="1988" <?php if($anneeEtud==="1988") { echo 'selected="selected"';}?>>1988</option>
                    <option value="1987" <?php if($anneeEtud==="1987") { echo 'selected="selected"';}?>>1987</option>
                    <option value="1986" <?php if($anneeEtud==="1986") { echo 'selected="selected"';}?>>1986</option>
                    <option value="1985" <?php if($anneeEtud==="1985") { echo 'selected="selected"';}?>>1985</option>
                    <option value="1984" <?php if($anneeEtud==="1984") { echo 'selected="selected"';}?>>1984</option>
                    <option value="1983" <?php if($anneeEtud==="1983") { echo 'selected="selected"';}?>>1983</option>
                    <option value="1982" <?php if($anneeEtud==="1982") { echo 'selected="selected"';}?>>1982</option>
                    <option value="1981" <?php if($anneeEtud==="1981") { echo 'selected="selected"';}?>>1981</option>
                    <option value="1980" <?php if($anneeEtud==="1980") { echo 'selected="selected"';}?>>1980</option>
                    <option value="1979" <?php if($anneeEtud==="1979") { echo 'selected="selected"';}?>>1979</option>
                    <option value="1978" <?php if($anneeEtud==="1978") { echo 'selected="selected"';}?>>1978</option>
                    <option value="1977" <?php if($anneeEtud==="1977") { echo 'selected="selected"';}?>>1977</option>
                    <option value="1976" <?php if($anneeEtud==="1976") { echo 'selected="selected"';}?>>1976</option>
                    <option value="1975" <?php if($anneeEtud==="1975") { echo 'selected="selected"';}?>>1975</option>
                    <option value="1974" <?php if($anneeEtud==="1974") { echo 'selected="selected"';}?>>1974</option>
                    <option value="1973" <?php if($anneeEtud==="1973") { echo 'selected="selected"';}?>>1973</option>
                    <option value="1972" <?php if($anneeEtud==="1972") { echo 'selected="selected"';}?>>1972</option>
                    <option value="1971" <?php if($anneeEtud==="1971") { echo 'selected="selected"';}?>>1971</option>
                </select>

                <label for="licenceEtud">Licence</label>
                <select name="licenceEtud" id="licenceEtud" required>
                    <option disabled="disabled" value="">Licence</option>
                    <option <?php if($licenceEtud==="L2 SPI") { echo 'selected="selected"';}?>>L2 SPI</option>
                    <option <?php if($licenceEtud==="L3 SPI") { echo 'selected="selected"';}?>>L3 SPI</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="small-12 columns text-center">
                <input type="checkbox" name="trouveStageEtud" id="trouveStageEtud" value="1" <?php if($trouveStageEtud==="1") { echo 'checked="checked"';}?>/>
                <label class="checkbox" for="trouveStageEtud">J'ai trouvé un stage</label>
            </div>
        </div>
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input class="large button expand" id="envoyer" type="submit" value="Enregistrer mes informations"/>
            </div>
        </div>
    </form>
<?php
    } else if ($_SESSION[type] === "entreprises" || (isset($_GET[idEnt]) && $_SESSION[type] === "admin")) {
            // Requète SQL
            if ($_SESSION[type] === "admin") {
                $query = "SELECT * FROM Entreprises WHERE idEnt='$_GET[idEnt]'";
            } else {
                $query = "SELECT * FROM Entreprises WHERE mailEnt='$_SESSION[identifiant]'";
            }
            // Exécution de la requète
            $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));  
            // Affichage des resultats
            $data = mysqli_fetch_assoc($result);
            
            $nomEnt=$data[nomEnt];
            $mailEnt=$data[mailEnt];
            $nomContactEnt=$data[nomContactEnt];
            $prenomContactEnt=$data[prenomContactEnt];
            $telEnt=$data[telEnt];
            $adresseEnt=$data[adresseEnt];
            $telSecEnt=$data[telSecEnt];
?>
    <h1>Modif entreprise<?php if ($_SESSION[type] === "admin") echo '<a href="supprimercompte?idEnt='.$_GET[idEnt].'"><button class="float_r" onclick="return confirm(\'Êtes-vous sur de vouloir supprimer définitivement ce compte?\');">Supprimer le compte</button></a>'?></h1>
    <form action="majinfodb" method="POST" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEnt') && checkPass('mdpEnt','mdpEnt2'))">
        <div class="col_13 float_l">
            <?php if ($_SESSION[type] === "admin") echo '<input type="hidden" name="mailEnt" value='.$mailEnt.' />'; ?>	            
            <label for="profilpic">Modifier le logo de l'entreprise :<br />
                <img src="fichiers/profile/<?php echo md5($mailEnt).".png" ?>" alt="Logo de l'entreprise" class="img_float_l img_frame" id="logo" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"'/>
            </label>
            <input type="file" name="profilpic" id="profilpic"/>
            <div class="cleaner h10"></div>	
                		
    		<label for="nomEnt">Nom de l'entreprise</label>
    		<input type="text" name="nomEnt" id="nomEnt" maxlength="100" required="required" value="<?php echo $nomEnt;?>"/>
    		<div class="cleaner h10"></div>
            
    		<label for="mailEnt">Mail entreprise (Identifiant)</label>
    		<input type="email" name="mailEnt" id="mailEnt" maxlength="100" required="required" value="<?php echo $mailEnt;?>"/>
            <div class="cleaner h10"></div>
            
            <label for="mdpEnt">Mot de passe</label>
    		<input type="password" name="mdpEnt" id="mdpEnt" maxlength="25" required="required" value="Defaut123" onkeyup="checkPatern('mdpEnt'); return false;"/>
            <span id="confirmPatern" class="confirmPatern"></span>
            <div class="cleaner h10"></div>
             
            <label for="mdpEnt2">Confirmer mot de passe</label>
    		<input type="password" name="mdpEnt2" id="mdpEnt2" maxlength="25" required="required" value="Defaut123" onkeyup="checkPass('mdpEnt','mdpEnt2'); return false;"/>
            <span id="confirmMessage" class="confirmMessage"></span>
            <div class="cleaner h10"></div>                
           
            <label for="prenomContactEnt">Prenom de contact</label>
    		<input type="text" name="prenomContactEnt" id="prenomContactEnt" maxlength="50" required="required" value="<?php echo $prenomContactEnt;?>"/>
    		<div class="cleaner h10"></div>
            
            <label for="nomContactEnt">Nom de contact</label>
    		<input type="text" name="nomContactEnt" id="nomContactEnt" maxlength="50" required="required" value="<?php echo $nomContactEnt;?>"/>
    		<div class="cleaner h10"></div> 
    	</div>
        
        <div class="col_23 float_r">
            <label>Localisez votre entreprise : </label>
            <input id="lat" type="hidden" name="latEnt" value="<?php echo $data[latEnt];?>"/>
            <input id="lng" type="hidden" name="lngEnt" value="<?php echo $data[lngEnt];?>" />        
            <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyAYxu_N0zElJPTPoVD1f3ih-IrrINGwMIU"
            type="text/javascript"></script>
            <div id="map" style="width: 516px; height: 320px"><br/></div>         
            <script type="text/javascript">
            //<![CDATA[
            loadmap(<?php echo json_encode($data[latEnt]);?>,<?php echo json_encode($data[lngEnt]);?>);
            //]]>
            </script>            
    
            <div class="cleaner h20"></div>              
            <label for="telEnt">Téléphone </label>
    		<input placeholder="Principal" type="tel" maxlength="6" name="telEnt" id="telEnt" value="<?php echo $telEnt;?>" onkeyup="verif_nombre(this)" required="required" />
    		<input placeholder="Secondaire" type="tel" maxlength="6" name="telSecEnt" id="telSecEnt" value="<?php echo $telSecEnt;?>" onkeyup="verif_nombre(this)" />
    		<div class="cleaner h10"></div>
            
            <label for="adresseEnt">Adresse</label>
    		<textarea name="adresseEnt" id="adresseEnt" maxlength="255" required="required"><?php echo $adresseEnt;?></textarea>
            
        </div>
        <div class="cleaner h40"></div>
        <div class="centrer"><button type="submit">Mettre à jour mes informations</button></div>
    </form>
<?php
            } else if ($_SESSION[type] === "admin") {
                    // Requète SQL
                    $query = "SELECT * FROM Administrateurs WHERE mailAdmin='$_SESSION[identifiant]'";
                    // Exécution de la requète
                    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));  
                    // Affichage des resultats
                    $data = mysqli_fetch_assoc($result);
                    
                    $nomAdmin=$data[nomAdmin];
                    $prenomAdmin=$data[prenomAdmin];
                    $mailAdmin=$data[mailAdmin];
?>
    <h1>Profil Administrateur</h1>
    <form action="majinfodb" method="POST" enctype="multipart/form-data" onsubmit="return (checkPatern('mdpEtud') && checkPass('mdpEtud','mdpEtud2'))"> 
        <div class="col_23 float_l">
    		<label for="mailAdmin">Identifiant (Non modifiable)</label>
    		<input placeholder="<?php echo $mailAdmin;?>" type="text" name="mailAdmin" id="mailAdmin" maxlength="100" required="required" disabled="disabled"/>
        	<div class="cleaner h10"></div>
            
    		<label for="mdpAdmin">Mot de passe (Un chiffre, une majuscule, une minuscule, minimum 6 caractères)</label>
    		<input type="password" name="mdpAdmin" id="mdpAdmin" maxlength="25" required="required" onkeyup="checkPatern('mdpAdmin'); return false;" value="Defaut123"/>
            <span id="confirmPatern" class="confirmPatern"></span>
            <div class="cleaner h10"></div>
            
            <label for="mdpAdmin2">Confirmer mot de passe</label>
    		<input type="password" name="mdpAdmin2" id="mdpAdmin2" maxlength="25" required="required" onkeyup="checkPass('mdpAdmin','mdpAdmin2'); return false;" value="Defaut123"/>
            <span id="confirmMessage" class="confirmMessage"></span>
            <div class="cleaner h10"></div>
    
            <label for="prenomAdmin">Prenom</label>
    		<input type="text" name="prenomAdmin" id="prenomAdmin" maxlength="50" required="required" value="<?php echo $prenomAdmin;?>"/>
            <div class="cleaner h10"></div>
            
            <label for="nomAdmin">Nom</label>
    		<input type="text" name="nomAdmin" id="nomAdmin" maxlength="50" required="required" value="<?php echo $nomAdmin;?>" />
    		<div class="cleaner h10"></div>
                        
        </div>
        
        <div class="col_13 float_r">
            <label for="profilpic" >Modifier ma photo de profile - <a href="javascript:window.location.reload()">Actualiser</a><br />
                <img src="fichiers/profile/<?php echo md5($mailAdmin).".png" ?>" alt="Photo de profile" class="img_float_l img_frame" onerror='this.onerror = null; this.src="./fichiers/profile/defaut.png"' />
            </label>
            <input type="file" name="profilpic" id="profilpic"/>
            <div class="cleaner h10"></div>	
        </div>
		<div class="cleaner h40"></div>
		<div class="centrer">
            <button id="envoyer" type="submit">Enregistrer mes informations</button>
        </div>
    </form>

<?php
                }
include('all.footer.php');
?>