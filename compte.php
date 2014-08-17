<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] === "etud") {
    ?>
    <div class="row">
        <div class="large-6 column">
            <a href="./majinfo" class="large button expand">Modifier mes informations</a>
        </div>
        <div class="large-6 column">
            <a href="./cv" class="large button expand">Uploader ou consulter mon CV</a>
        </div>
    </div>
<?php
} else if ($_SESSION['connected'] === "ent") {
    ?>
    <!--<div class="row">
        <div class="large-6 column">
            <a href="./majinfo" class="large button expand">Modifier mes informations</a>
        </div>
    </div>-->
    <?php
    header('Location: ./majinfo');
} else if ($_SESSION['connected'] === "admin") {
    ?>
    <div class="row">
        <div class="large-6 column">
            <a href="./majinfo" class="large button expand">Modifier mes informations</a>
        </div>
<!--        <div class="large-6 column">
            <a href="./cv" class="large button expand">Uploader ou consulter mon CV</a>
        </div>-->
    </div>
<?php
} else {
    header('Location: index');
    die();
}
include('all.footer.php');
?>