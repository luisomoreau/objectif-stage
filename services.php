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
} else if ($_SESSION['connected'] === "admin") {
    ?>
    <div class="row">
        <div class="large-4 columns">
            <a href="./listeentreprises" class="large button expand">Liste des entreprises</a>
        </div>
        <div class="large-4 columns">
            <a href="./listeetudiants" class="large button expand">Liste des étudiants</a>
        </div>
        <div class="large-4 columns">
            <a href="./listestages" class="large button expand">Liste des stages</a>
        </div>
    </div>
    <div class="row">
        <div class="large-4 columns">
            <a href="./listemails" class="large button expand">Liste des emails</a>
        </div>
        <div class="large-4 columns">
            <a href="./mail" class="large button expand">Envoyer un mail</a>
        </div>
        <div class="large-4 columns">
            <a href="./pma" class="large button expand">phpMyAdmin</a>
        </div>
    </div>
<?php
} else {
    header('Location: ./');
    die();
}
include('all.footer.php');
?>