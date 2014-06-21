<?php
/****idée conneixon**********/
session_start();
if (!(isset($_SESSION['connected']))) {
    header('location: ./');
    die();
}
?>