<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] === "etud") {
    ?>
    <section class="row">
        <div class="small-12 columns">
            <h1>Les Services</h1>
        </div>
    </section>
    <section class="row">
        <div class="large-6 column">
            <a href="./listestages" class="large button expand">Liste des stages</a>
        </div>
        <div class="large-6 column">
            <a href="./listeentreprises" class="large button expand">Liste des entreprises</a>
        </div>
    </section>
<?php
} else if ($_SESSION['connected'] === "ent") {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <h1>Les services proposés pas la plateforme</h1>
        </div>
    </div>
    <div class="row">
        <div class="large-6 columns">
            <a href="./listestages" class="large button expand">Liste des stages</a>
        </div>
        <div class="large-6 columns">
            <a href="./listeentreprises" class="large button expand">Liste des entreprises</a>
        </div>
    </div>
    <div class="row">
        <div class="large-6 columns">
            <a href="./depotstage" class="large button expand">Proposer un stage</a>
        </div>
        <div class="large-6 columns">
            <a href="./messtages" class="large button expand">Mes stages</a>
        </div>
    </div>
<?php
} else if ($_SESSION['type'] === "admin") {
    ?>
    <div class="col_23 float_l">
        <h1>Services</h1>
        <a href="listeentreprises">
            <button class="big_button">Liste des entreprises</button>
        </a>
        <a href="listeetudiants">
            <button class="big_button">Liste des étudiants</button>
        </a>
        <a href="listestages">
            <button class="big_button">Liste des stages</button>
        </a>
        <a href="listemails">
            <button class="big_button">Liste des emails</button>
        </a>
        <!--<a href="statistiques"><button class="big_button" >Statistiques</button></a>-->
        <a href="mail">
            <button class="big_button">Envoyer un mail</button>
        </a>
        <a href="pma">
            <button class="big_button">phpMyAdmin</button>
        </a>
    </div>
    <div class="col_13 float_r">
        <div class="cleaner h20"></div>
        <h4>Informations</h4>

        <p>En tant qu'administrateur, vous avez accés à toutes les données de la plateforme.</p>
    </div>
    <div class="cleaner h10"></div>
<?php
} else {
    header('Location: ./');
    die();
}
include('all.footer.php');
?>