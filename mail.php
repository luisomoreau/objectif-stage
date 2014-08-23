<?php
include('all.header.php');
include('logincheck.php');
$user = getInfos();
if ($user == null) {
    header('Location: ./');
    die();
}
if (isset($_POST['cv']) && isset($_POST['dest']) && isset($_POST['cible']) && isset($_POST['sujet']) && isset($_POST['message'])) {
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
    //Si l'étudiant envoie un mail on ajoute 1 à ses candidatures
    $stmt = $mysqli->prepare('UPDATE etudiants SET nbCandEtud=nbCandEtud+1 WHERE userEtud=?');
    $stmt->bind_param('s', $_SESSION['identifiant']);
    $stmt->execute();
    $stmt->close();

    $destinataire = stripslashes($_POST['dest']);
    $sujet = stripslashes($_POST['sujet']);
    $message = stripslashes(nl2br($_POST['message']));
    $expediteur = $user->mail;
    $nom_expediteur = $user->displayName;
    $cv = "./fichiers/cv/" . md5($_SESSION['identifiant']) . ".pdf";
    if (file_exists($cv)) {
        if ($_POST['cv'] === "1") {
            $piece_jointe = $cv;
        } else {
            $piece_jointe = '0';
        }
    } else {
        $piece_jointe = '0';
    }

    //check si le mail existe
    $exist = 0;
    if ($_POST['cible'] == 'stage') {
        $stmt = $mysqli->prepare('SELECT E.idEnt FROM stages,entreprises as E WHERE stages.idEnt=E.idEnt AND mailContactStage=?');
        $stmt->bind_param('s', $_POST['dest']);
        $stmt->execute();
        $stmt->bind_result($idEnt);
        $stmt->fetch();
        $stmt->close();
    } else {
        if ($_POST['cible'] == 'ent') {
            $stmt = $mysqli->prepare('SELECT idEnt FROM entreprises WHERE mailEnt=?');
            $stmt->bind_param('s', $_POST['dest']);
            $stmt->execute();
            $stmt->bind_result($idEnt);
            $stmt->fetch();
            $stmt->close();
        }
    }
    if (!is_null($idEnt)) {//si le mail existe et correspon bien à la cible demandée (stage : réponse à une offre, ent : proposition volontaire)
        if (email($mail_account, $mail_pwd, $destinataire, $sujet, $message, $expediteur, $nom_expediteur, $piece_jointe)) {
            $stmt = $mysqli->prepare('INSERT INTO mail (userEtud, idEnt, destinataireMail, sujetMail, messageMail, cvMail)
                        VALUES (?,?,?,?,?,?)');
            $stmt->bind_param('sissss', $_SESSION['identifiant'], $idEnt, $destinataire, $sujet, $message, $piece_jointe);
            $stmt->execute();
            $stmt->close();
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
        header('location: ./');
        die();
    }
} else {
    if (isset($_GET['cible']) && isset($_GET['mail']) && ($_GET['cible'] == 'ent' || $_GET['cible'] == 'stage')) {
        ?>
        <form method="post" action="mail">
            <div class="row panel">
                <div class="row collapse">
                    <div class="small-4 columns">
                        <span class="prefix">Expediteur : </span>
                    </div>
                    <div class="small-8 columns">
                        <input type="text" value="<?php echo $user->displayName . ' (' . $user->mail . ')'; ?>" disabled>
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-4 columns">
                        <span class="prefix">Destinataire : </span>
                    </div>
                    <div class="small-8 columns">
                        <input type="text" value="<?php echo $_GET['mail']; ?>" disabled>
                        <input type="hidden" name="cible" value="<?php echo $_GET['cible']; ?>">
                        <input type="hidden" name="dest" value="<?php echo $_GET['mail']; ?>">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-4 columns">
                        <span class="prefix">Sujet : </span>
                    </div>
                    <div class="small-8 columns">
                        <input type="text" name="sujet" value="Stage : ">
                    </div>
                </div>
                <div class="row collapse">
                    <div class="small-8 columns">
                        <span class="prefix">Souhaitez vous joindre votre cv ?</span>
                    </div>
                    <div class="small-4 columns">
                        <div class="switch">
                            <input id="cv" name="cv" type="radio" value="0">
                            <label for="cv" onclick="" class="text-center">Non</label>

                            <input id="cv" name="cv" type="radio" value="1" checked>
                            <label for="cv" onclick="" class="text-center">Oui</label>

                            <span></span>
                        </div>
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
    } else {
        header('location: ./');
        die();
    }
}
include('all.footer.php');