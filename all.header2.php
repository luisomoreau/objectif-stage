<?php
require('settings.php');
//require('logincheck.php');
require('fonctions.php');
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
                        <li class="first leaf color-1"><a href="./index2" class="has-icon active"><i class="fi-home"></i>Accueil</a></li>
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
            <div class="large-8 columns">
                <h1 id="page-title" class="title">Accueil</h1>
            </div>
            <div class="large-4 columns" style="margin-top:50px;">
<!--                --><?php
//                    if ($auth) {
//                        echo '<div>Bonjour '.phpCAS::getUser().' | <a href="?logout=">Déconnexion</a></div>';
//                    }
//                ?>

            </div>
        </div>
    </section>
    <!--fin header-->