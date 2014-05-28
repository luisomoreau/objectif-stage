<?php 
    include('all.header.php');
    if ($_SESSION[type]==="etudiants") {
?>
        <div class="col_23 float_l">
        <h1>Services</h1>
            <a href="listestages"><button class="big_button">Liste des stages</button></a>
            <a href="listeentreprises"><button class="big_button">Liste des entreprises</button></a>
        </div>
        <div class="col_13 float_r">
            <div class="cleaner h20"></div>
            <h4>Informations</h4>
            <p>Voici les services qui vous sont proposés par la plateforme.</p>
        </div>
        
<?php
    } else if ($_SESSION[type]==="entreprises") {               
?>
        <div class="col_23 float_l">
            <h1>Services</h1>
            <a href="listestages"><button class="big_button">Liste des stages</button></a>
            <a href="listeentreprises"><button class="big_button">Liste des entreprises</button></a>
            <a href="depotstage"><button class="big_button">Déposer un stage</button></a>
            <a href="messtages"><button class="big_button">Mes stages</button></a>
        </div>
        <div class="col_13 float_r">
            <div class="cleaner h20"></div>
            <h4>Informations</h4>
            <p>Voici les services qui vous sont proposés par la plateforme.</p>
        </div>
        <div class="cleaner h10"></div>
<?php
        } else if ($_SESSION[type] === "admin") {
?>
        <div class="col_23 float_l">
            <h1>Services</h1>
            <a href="listeentreprises"><button class="big_button" >Liste des entreprises</button></a>
            <a href="listeetudiants"><button class="big_button" >Liste des étudiants</button></a>
            <a href="listestages"><button class="big_button" >Liste des stages</button></a>   
            <a href="listemails"><button class="big_button" >Liste des emails</button></a>  
            <!--<a href="statistiques"><button class="big_button" >Statistiques</button></a>-->
            <a href="mail"><button class="big_button" >Envoyer un mail</button></a>
            <a href="pma"><button class="big_button" >phpMyAdmin</button></a>  
        </div>
        <div class="col_13 float_r">
            <div class="cleaner h20"></div>
            <h4>Informations</h4>
            <p>En tant qu'administrateur, vous avez accés à toutes les données de la plateforme.</p>
        </div>
        <div class="cleaner h10"></div>
<?php   
        } else {
            header('Location: index');
            die();
        }
    include('all.footer.php');
?>