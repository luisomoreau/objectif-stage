<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected'] !== "admin") {
    realDie();
} else {
?>
    <div class="row panel">
        <div class="large-12 columns">
            NIE
        </div>
    </div>
<?php
}
include('all.footer.php');