<?php
require('settings.php');
require('logincheck.php');
require('fonctions.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Objectif Stage</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="Description" content="Plateforme de gestion des stages de l'UNC." />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <script type='text/javascript' src='js/jquery.min.js'></script>
    <script type='text/javascript' src='js/jquery.scrollTo-min.js'></script>
    <script type='text/javascript' src='js/jquery.localscroll-min.js'></script>
    <script type='text/javascript' src='js/javascript.js'></script>
    <script type="text/javascript">
        $(document).ready(function () {
        $.localScroll();
        });
    </script> 
</head>   

<body>
<div id="fond_haut"></div>
<div id="fond">
<div id="conteneur">
    <div id="header">
        <div class="logo">
            <a title="Accueil" href="/"><img src="./images/logo.png" alt="Accueil" /></a>
        </div>
        <div class="infos">       
        <?php 
            if ($logged === 1) {
                echo 'Bienvenue '.$nom_disp.'<br /><a href="logout">Deconnexion</a>';
            } else {
                $redirect = basename($_SERVER["SCRIPT_FILENAME"], '.php');
                echo '<div id="login_link" >Veuillez vous connecter<br/><a href="#" onclick="document.getElementById(\'login_form\').style.display=\'\'; document.getElementById(\'login_link\').style.display=\'none\';">Connexion</a></div>
                <div id="login_form" style="display:none"><form action="login';
                if (isset($_GET['redirect'])) echo "?redirect=".$_GET['redirect'];
                echo '" method="POST" id="login">
                <input type="hidden" name="login" value=""/>
                <input style="width: 220px;" placeholder="Votre e-mail" type="email" name="identifiant" id="identifiant" maxlength="100" required="required" /><br />
                <input style="width: 139px;" placeholder="Mot de passe" type="password" name="mdp" id="mdp" maxlength="25" required="required" />
                <button type="submit">Connexion</button>
                </form></div>';
            }
        ?>
        </div>
    </div>
    
    <div id="menu">
    	<ul>
        	<li><a href="./" class="accueil"><span></span></a></li>
            <li><a href="./services" class="services"><span></span></a></li>
            <li><a href="./documents" class="documents"><span></span></a></li>
            <li><a href="./compte" class="compte"><span></span></a></li>
            <li><a href="./contact" class="contact"><span></span></a></li>
		</ul>
    </div>
    
    <div id="main">