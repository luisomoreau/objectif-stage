<?php 
include('all.header.php');
echo '<section class="row">
            <div class="small-12 columns">
                <h1>Mon compte</h1>
            </div>
         </section>';
if ($_SESSION['connected']==="etud") {
    echo '
    <section class="row">
        <div class="large-6 column">
            <a href="./majinfo" class="large button expand">Modifier mes informations</a>
        </div>
        <div class="large-6 column">
            <a href="./cv" class="large button expand">Uploader ou consulter mon CV</a>
        </div>
    </section>';
} else if ($_SESSION['connected']==="ent") {
?>
    <h1>Mon compte</h1>
    <div class="col">
        <a href="majinfo"><button class="big_button">Mes infos</button></a>
    </div>
<?php
        } else if ($_SESSION['connected']==="admin") {
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