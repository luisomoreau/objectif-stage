<?php 
include('all.header.php');
if ($_SESSION[type]==="etudiants") {
?>
    <h1>Mon compte</h1>
    <div class="col">
        <a href="majinfo"><button class="big_button">Mes infos</button></a>
        <a href="cv"><button class="big_button">Mon CV</button></a>
    </div>
    
<?php
} else if ($_SESSION[type]==="entreprises") {
?>
    <h1>Mon compte</h1>
    <div class="col">
        <a href="majinfo"><button class="big_button">Mes infos</button></a>
    </div>
<?php
        } else if ($_SESSION[type]==="admin") {
?>
                    <h1>Mon compte</h1>
                    <div class="col">
                        <a href="majinfo"><button class="big_button">Mes infos</button></a>
                        <a href="messagescontact"><button class="big_button">Mes messages</button></a>
                    </div>
<?php                    
            } else {
                header('Location: index');
                die();
            }
include('all.footer.php');
?>