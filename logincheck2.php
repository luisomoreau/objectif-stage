<?php
/****idée conneixon**********/
if (!(isset($_SESSION['connected']))) {
    header('location: ./');
    die();
}
?>