<?php
session_start();
require_once('settings.php');
require_once('fonctions.php');

?>
<!doctype html>
<html lang="fr">
<head>
    <title>Objectif Stage</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="Description" content="Plateforme de gestion des stages de l'UNC." />
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="scss/custom.css"/>
    <link rel="stylesheet" href="css/foundation-icons/foundation-icons.css"/>
    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/javascript.js"></script>
    <script src="js/vendor/custom.modernizr.js"></script>
</head>
<body>
<!--.page -->
<div role="document" class="page">
    <!--.l-header region -->
    <header role="banner" class="l-header">

        <div class="header-top hide-for-small">
            <div class="row">
                <div class="large-6 columns header_top_left">
                    <ul class="social-icons accent inline-list">

                    </ul>
                </div>
                <div class="large-6 columns header_top_right text-right">
                </div>
            </div>
        </div>
        <!--.top-bar -->
        <div class="contain-to-grid sticky">
            <nav class="top-bar" data-options="">
                <ul class="title-area">
                    <li class="name"><h1><a href="./" rel="home" title="Objectif Stage" class="active">Objectif Stage !</a></h1></li>
                    <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
                </ul>
                <section class="top-bar-section">
                    <ul id="main-menu" class="main-nav">
                        <li class="first leaf color-1"><a href="./" class="has-icon active"><i class="fi-home"></i>Accueil</a></li>
                        <?php
                            if (isset($_SESSION['connected'])) {
                                echo '
                                    <li class="expanded color-2" title=""><a href="./services" title="" class="has-icon"><i class="fi-address-book"></i>Services</a></li>
                                    <li class="leaf color-3"><a href="./documents" class="has-icon"><i class="fi-page"></i>documents</a></li>
                                    <li class="last leaf color-4"><a href="./compte" class="has-icon"><i class="fi-torso"></i>Mon compte</a></li></ul>
                                ';
                            }
                        ?>
<!--                            <ul class="dropdown">-->
<!--                                <li class="expanded color-2 show-for-small"><a href="/etudiant" title="">Espace étudiant</a>-->
<!--                                <li class="first leaf" target="_blank"><a href="http://lee.univ-nc.nc/cgi-bin/WebObjects/ipWeb.woa" target="_blank">Notes</a></li>-->
<!--                                <li class="last leaf" target="_blank"><a href="http://tic.univ-nc.nc/" target="_blank">Campus virtuel</a></li>-->
<!--                            </ul>-->
                </section>
            </nav>
        </div>
    </header>
    <section class="titlebar ">
        <div class="row">
            <div class="small-8 columns">
                <h1 id="page-title" class="title">
                    <?php
                    $page = substr(substr(strrchr($_SERVER['SCRIPT_NAME'], "/"), 1),0,-4);
                    switch($page) {
                        case "cv":
                            $page = "Mon CV";
                            break;
                    }
                    echo $page;
                    ?>
                </h1>
            </div>
            <div class="small-4 columns">
                <?php
                    if (isset($_SESSION['connected'])) {
                        if ($_SESSION['connected']=="etud") {
                            echo '<div class="text-right" style="margin-top:50px;">Bonjour '.$_SESSION['identifiant'].' | <a href="./logout">Déconnexion</a></div>';
                        }
                        if ($_SESSION['connected']=="ent") {
                            echo '<div class="text-right" style="margin-top:50px;">Bonjour '.$_SESSION['identifiant'].' | <a href="./logout">Déconnexion</a></div>';
                        }
                    }
                ?>

            </div>
        </div>
    </section>
    <!--fin header-->