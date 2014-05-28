<?php
$loginreq=0;
include('all.header.php');

if (!isset($_GET[id])) {
    header('Location : /');
    die();
}
echo "<h1>Erreur $_GET[id]:</h1>";
switch ($_GET[id]) {
    case 401:
        echo "<strong><p>Vous ne pouvez acceder à cette page sans authentification.</p></strong>";
        break;
    case 402:
        echo "<strong><p>Paye les sous!</p></strong>";
        break;
    case 403:
        echo "<strong><p>Vous n'avez pas le droit d'accéder à cette page.</p></strong>";
        break;
    case 404:
        echo "<strong><p>La page que vous avez demandé n'existe pas.</p></strong>";
        break;
    case 405:
        echo "<strong><p>Wtf are you trying to do?</p></strong>";
        break;
    case 500:
        echo "<strong><p>Erreur interne au server, veuillez réessayer plus tard.</p></strong>";
        break;
    case 502:
        echo "<strong><p>Mauvaise réponse envoyée à un serveur intermédiaire par un autre serveur.</p></strong>";
        break;
    default:
        echo "<strong><p>Un erreur est survenue, veuillez réessayer plus tard</p></strong>";
        break;
}

include('all.footer.php');
?>