<?php
include('all.header.php');
include('logincheck.php');
if ($_SESSION['connected']!='admin') {
    realDie();
}
if (isset($_POST['expediteur']) && isset($_POST['destinataire']) && isset($_POST['sujet']) && isset($_POST['message'])) {
    if (email($mail_account, $mail_pwd, $_POST['destinataire'], stripslashes($_POST['sujet']), stripslashes(nl2br($_POST['message'])), 'stages@univ-nc.nc', $_POST['expediteur'], '0')) {
        ?>
        <div class="row">
            <div class="large-12 columns">
                <h3>Mail Envoyé !</h3>
            </div>
        </div>
    <?php
    } else {
        ?>
        <div class="row">
            <div class="large-12 columns">
                <h3>le mail n'a pas été envoyé, veuillez réessayer</h3>
            </div>
        </div>
    <?php
    }
} else {
?>
<form method="post">
            <div class="row panel">
                <div class="row collapse">
                    <div class="small-4 columns">
                        <span class="prefix">Nom expediteur : </span>
                    </div>
                    <div class="small-8 columns">
                        <input type="text" name="expediteur" required>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-4 columns">
                        <span class="prefix">Destinataire : </span>
                    </div>
                    <div class="small-8 columns">
                        <input type="email" name="destinataire" required value="<?php if(isset($_GET['dest'])) echo $_GET['dest']; ?>">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-4 columns">
                        <span class="prefix">Sujet : </span>
                    </div>
                    <div class="small-8 columns">
                        <input type="text" name="sujet" required>
                    </div>
                </div>
                <div class="row">
                    <div class="large-12 columns">
                        <textarea id="mail" name="message" maxlength="1000" rows="10" required></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="large-centered large-6 columns">
                    <input class="large button expand" id="envoyer" type="submit" value="Envoyer"/>
                </div>
            </div>
        </form>
<?php
}
include('all.footer.php');