<?php
include ('all.header.php');
?>
        <h1>Liste des stages</h1>
        <div id="col">
        <h3>Recherche</h3>
            <form action="listestages" method="GET" id="recherche">
                <div class="col_12 float_l">
            		<input placeholder="Mots-clés" type="text" name="champ_rech" id="champ_rech" maxlength="100" value="<?php if (isset($_GET['champ_rech'])) { echo $_GET['champ_rech']; } ?>" />
            		<div class="cleaner h10"></div>

                    <input type="checkbox" name="htmlcssStage" id="htmlcssStage" value="1" <?php if(isset($_GET['htmlcssStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="htmlcssStage" >HTML/CSS</label>

                    <input type="checkbox" name="phpStage" id="phpStage" value="1" <?php if(isset($_GET['phpStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="phpStage" >PHP</label>

                    <input type="checkbox" name="sqlStage" id="sqlStage" value="1" <?php if(isset($_GET['sqlStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="sqlStage" >SQL</label>

                    <input type="checkbox" name="javaStage" id="javaStage" value="1" <?php if(isset($_GET['javaStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="javaStage" >JAVA</label>

                    <input type="checkbox" name="cStage" id="cStage" value="1" <?php if(isset($_GET['cStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="cStage" >C</label>

                    <input type="checkbox" name="csStage" id="csStage" value="1" <?php if(isset($_GET['csStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="csStage" >C#</label>
                </div>
                <div class="col_12 float_r"> 
                    <input type="checkbox" name="expStage" id="expStage" value="1" <?php if(isset($_GET['expStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="expStage" >Afficher même les stages expirés</label>
                    <div class="cleaner h10"></div>
                    
                    <input type="checkbox" name="remStage" id="remStage" value="1" <?php if(isset($_GET['remStage'])) { echo 'checked="checked"';}?>/>
                    <label class="checkbox" for="remStage" >Rémunéré</label> -  
                    <select name="licenceEtud">
                        <option>Niveau</option>
                        <option <?php if(isset($_GET['licenceEtud']) and $_GET['licenceEtud']==="Stage L2") { echo 'selected="selected"';}?>>Stage L2</option>
                        <option <?php if(isset($_GET['licenceEtud']) and $_GET['licenceEtud']==="Stage L3") { echo 'selected="selected"';}?>>Stage L3</option>
                        <option <?php if(isset($_GET['licenceEtud']) and $_GET['licenceEtud']==="Projet Tuteuré") { echo 'selected="selected"';}?>>Projet Tuteuré</option>
                    </select> 
                </div>
                <div class="cleaner h30"></div>
                <div class="centrer">
                    <button type="submit">Rechercher</button>
                </div>
            </form>
        </div>
        <div class="cleaner h20"></div>
        <h3>Résultats</h3>
<?php
// Chargement des paramètres de la DB
require('sqlconf.php');
if (isset($_GET['champ_rech'])) {
    $query = "SELECT * FROM stages WHERE (nomStage LIKE '%".$_GET['champ_rech']."%'";
} else {
    $query = "SELECT * FROM stages WHERE (nomStage LIKE '%%'";
}

//echo $_GET[champ_rech];
if (isset($_GET['champ_rech'])) {
    $query.=" OR sujetStage LIKE '%".$_GET['champ_rech']."%'";
    $query.=" OR detailsStage LIKE '%".$_GET['champ_rech']."%')";
} else {
    $query.=")";
}
if (isset($_GET['htmlcssStage'])) {
    $query.=" AND htmlcssStage='1'";
}
if (isset($_GET['phpStage'])) {
    $query.=" AND phpStage='1'";
}
if (isset($_GET['sqlStage'])) {
    $query.=" AND sqlStage='1'";
}
if (isset($_GET['javaStage'])) {
    $query.=" AND javaStage='1'";
}
if (isset($_GET['cStage'])) {
    $query.=" AND cStage='1'";
}
if (isset($_GET['csStage'])) {
    $query.=" AND csStage='1'";
}
if (isset($_GET['licenceEtud']) && ($_GET['licenceEtud']!=="Niveau")) {
    $query.=" AND typeStage='$_GET[licenceEtud]'";
}
if (isset($_GET['remStage'])) {
    $query.=" AND remuStage='1'";
}
if (!isset($_GET['expStage'])) {
    $query.=" AND TO_DAYS(NOW()) < TO_DAYS(dateLimiteStage)";
}

// Connection SQL
$dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));
// Exécution de la requète pour les colones
$result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
// Affichage des colones
$data = mysqli_fetch_assoc($result);
if ($data == NULL) {
    echo "Aucun résultat";
} else {
    echo "<table>";
    echo "<tr><th>Nom du stage</th><th>Lieu</th><th>Sujet</th><th>Début du stage</th><th>Durée du stage</th><th>Options</th></tr>";
    // Exécution de la requète pour les valeures
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: ".mysqli_error($dblink));
    // Remplissage du tableau
    while($data = mysqli_fetch_assoc($result)) {
        echo '<tr onclick="document.location.href=\'infostage?id='.$data['idStage'].'\'">';
        echo "<td>$data[nomStage]</td><td>$data[lieuStage]</td><td>$data[sujetStage]</td><td>".utf8_encode (strftime("%#d %B %Y",strtotime($data['dateDebutStage'])))."</td><td>$data[dureeStage] jours</td>";
        echo '<td><a href="infostage?id='.$data['idStage'].'">Plus d\'infos</a></td>';
        /** Mettre des petites images choc logo des languages **/
        echo "</tr>";
    }
    // On ferme le tableau
    echo "</table>";
}
include ('all.footer.php');
?>