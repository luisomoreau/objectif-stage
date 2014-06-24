<?php
include('nontraite.php');
$loginreq=0;
include('all.header.php'); 
if (isset($_POST['login'])) {
    // Chargement des param�tres de la DB
    require('sqlconf.php');
    
    // Connection SQL
    $dblink = mysqli_connect($sqlserver,$sqlid,$sqlpwd,$sqldb) or die("Erreur de connection au server SQL: ".mysqli_error($dblink));
    
    // Requ�te SQL
    $login=$_POST['identifiant'];
    $login=str_replace("'","\'",$login);
    $mdp=md5($_POST['mdp'].$salt);
    $query = "SELECT COUNT(*) as 'existant', valideEtud, dateInscriptionEtud, idEtud FROM etudiants WHERE ((mailEtud = '".$login."' AND  mdpEtud = '".$mdp."') OR (mailPersoEtud = '".$login."' AND  mdpEtud = '".$mdp."'))";
    $query2 = "SELECT COUNT(*) as 'existant', idEnt, valideEnt FROM entreprises WHERE (mailEnt = '".$login."' AND  mdpEnt = '".$mdp."')";
    $query3 = "SELECT COUNT(*) as 'existant' FROM administrateurs WHERE (mailAdmin = '".$login."' AND  mdpAdmin = '".$mdp."')";
    
    
    // Ex�cution de la requ�te
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requ�te SQL: ".mysqli_error($dblink));
    $result2 = mysqli_query($dblink, $query2) or die("Erreur lors de la requ�te SQL: ".mysqli_error($dblink));
    $result3 = mysqli_query($dblink, $query3) or die("Erreur lors de la requ�te SQL: ".mysqli_error($dblink));
    $data = mysqli_fetch_assoc($result);
    $data2 = mysqli_fetch_assoc($result2);
    $data3 = mysqli_fetch_assoc($result3);
    
    //check validit� compte �tudiant - expiration date 
    if ((strtotime("now")-strtotime($data['dateInscriptionEtud'])>(31556926*2)) && (isset($data['dateInscriptionEtud']))) {
        //cas ou inscription expir�e 
        $setvalide ="DELETE FROM Etudiants WHERE mailEtud = '".$login."' AND  mdpEtud = '".$mdp."'";
        $setvalideresult = mysqli_query($dblink, $setvalide) or die("Erreur lors de la requ�te SQL: ".mysqli_error($dblink));
        include('all.footer.php'); 
        ?>
        <script> 
        alert('Votre compte a expir\351 veuillez vous r\351inscrire');
        window.location = "/";
         </script>
         <?php
         die();
    }
    if ($data['existant']==1 || $data2['existant']==1 || $data3['existant']==1) {
        if (($data['valideEtud']==1) || ($data2['valideEnt']==1 || $data3['existant']==1)) { //mettre � 1 d�s que valide
            $_SESSION['identifiant'] = $login;
            $_SESSION['mdp'] = $mdp;
            if (strpos($login, "etudiant.univ-nc.nc")) {
                $_SESSION['type'] = "etudiants";
                $_SESSION['idEtud'] = $data['idEtud'];
            } else if ($data2['existant']==1) {
                $_SESSION['type'] = "entreprises";
                $_SESSION['idEnt'] = $data2['idEnt'];
                } else {
                    $_SESSION['type']= "admin";
                }
            if (isset($_GET['redirect'])) {
                $redirect = $_GET['redirect'];
            } else {
                $redirect = "./";
            }
            header('Location: '.$redirect);
            die();
        } else {
            include('all.footer.php'); 
            ?>
            <script> 
            alert('Votre compte n\'a pas \351t\351 valid\351, veuillez consulter vos emails.');
            window.location = "login";
             </script>
             <?php
        }
    } else {
        include('all.footer.php'); 
        ?>
        <script> 
        alert('Utilisateur ou mot de passe erron\351.');
        window.location = "login";
         </script>
         <?php
    }  
} else {
    ?>
<h1>Connexion</h1>
<div class="centrer">
    <form action="login<?php if (isset($_GET['redirect'])) echo "?redirect=".$_GET['redirect']; ?>" method="POST" id="login">
        <input type="hidden" name="login" value=""/>
        
    	<input placeholder="Votre identifiant (E-mail)" type="email" name="identifiant" id="identifiant" maxlength="100" required="required" />
        <div class="cleaner h10"></div>
        
    	<input placeholder="Mot de passe" type="password" name="mdp" id="mdp" maxlength="25" required="required" />
        <div class="cleaner h10"></div>
        
        <button class="submit_btn" type="submit">Se connecter</button>
    </form>
</div>
    <?php
}  
include('all.footer.php'); 
?>