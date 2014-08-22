<?php
include('all.header.php');
if (isset($_POST['email']) && isset($_POST['captcha'])) {
    if (isset($_SESSION['captcha']) && $_POST['captcha'] == $_SESSION['captcha']) {
        $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
        $stmt = $mysqli->prepare('SELECT idEnt, mailEnt, mdpEnt FROM entreprises WHERE mailEnt=?');
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->bind_result($idEnt, $mailEnt, $mdpEnt);
        $stmt->fetch();
        $stmt->close();
        if (!is_null($idEnt)) {//si le mail existe
            $destinataire = $_POST['email'];
            $clef = hash('sha512', $mailEnt.$mdpEnt);
            if (email($mail_account, $mail_pwd, $destinataire, "Objectif stage : réinitialisation de votre mot de passe",
                "Bonjour,<br><br>Vous avez effectué une demande de réinitialisation de votre mot de passe, si c'est le cas, veuillez cliquer sur ce lien :<br><br><a href=\"https://stages.univ-nc.nc/rstpwd?clef=$clef\">Lien vers votre compte (pensez à changer votre mot de passe)</a>",
                "stages@univ-nc.nc", 'Plateforme Objectif stage', '0')) {
                ?>
                <div class="row">
                    <div class="large-12 columns">
                        <h3>Le email contenant un lien pour réinitialiser votre mot de passe vous a bien été envoyé .</h3>
                    </div>
                </div>
            <?php
            } else {
                ?>
                <div class="row">
                    <div class="large-12 columns">
                        <h3>Le mail n'a pas été envoyé, veuillez réessayer</h3>
                    </div>
                </div>
            <?php
            }
        } else {
            ?>
            <div class="row">
                <div class="large-12 columns">
                    <h3>Ce mail n'existe pas, veuillez réessayer</h3>
                </div>
            </div>
            <?php
        }
    } else {
        echo "FAILLL";
    }
} else {
    if (isset($_GET['clef'])) {

    }
}
$nb1 = mt_rand(1, 10);
$nb2 = mt_rand(1, 10);
$rep = $nb1 + $nb2;
$_SESSION['captcha'] = $rep;
?>
        <form method="POST">
            <div class="row">
                <div class="small-12 large-6 large-centered columns">
                    <input placeholder="Email de l'entreprise" type="email" name="email" id="email" maxlength="100"
                           required="required"/>
                </div>
            </div>
            <div class="row">
                <div class="small-12 large-6 large-centered columns">
                    <div class="row collapse">
                        <div class="small-6 columns">
                            <span class="prefix"><?php echo "$nb1 + $nb2 ="; ?></span>
                        </div>
                        <div class="small-6 columns">
                            <input placeholder="Réponse" type="tel" maxlength="2" name="captcha" id="captcha"
                                   onkeyup="verif_nombre(this)" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="small-12 large-6 large-centered columns">
                    <input class="large button expand" id="envoyer" name="submit" type="submit"
                           value="Se connecter"/>
                </div>
            </div>
        </form>


<?php
include('all.footer.php');
?>