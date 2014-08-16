<?php
include('all.header.php');
if ($_SESSION['connected'] !== "admin") {
    if ($_SESSION['connected'] == "entreprises" || (!isset($_GET['stage']) && !isset($_GET['ent']) && !isset($_POST['envoi_mail']))) {
        header('Location: /');
        die();
    }
}
$cv = "./fichiers/cv/" . md5($_SESSION['identifiant']) . ".pdf";
if (isset($_POST['envoi_mail'])) {
    if ($_POST['typemail'] === "stage") {
        $query = "UPDATE Etudiants SET nbCandEtud=nbCandEtud+1 WHERE mailEtud='$_SESSION[identifiant]'";
        $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: " . mysqli_error($dblink));
    }
    $destinataire = stripslashes($_POST['destinataire']);
    $sujet = stripslashes($_POST['sujet']);
    $message = stripslashes(nl2br($_POST['message']));
    $expediteur = $_SESSION['identifiant'];
    $nom_expediteur = stripslashes($_POST['nom_expediteur']);


    if ($_POST['cv'] === "1") {
        $piece_jointe = $cv;
    } else {
        $piece_jointe = 0;
    }
    $query = "INSERT INTO Mail (idEtud, destinataireMail, sujetMail, messageMail, expediteurMail, cvMail)
                        VALUES ('$_SESSION[idEtud]', '$destinataire2', '$sujet2', '$message2', '$nom_expediteur2', '$piece_jointe')";
    $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: " . mysqli_error($dblink));
    email($destinataire, $sujet, $message, $expediteur, $nom_expediteur, $piece_jointe);
} else {
    if (isset($_GET['stage'])) {
        // Requète SQL
        $query = "SELECT * FROM stages,entreprises WHERE stages.idEnt=entreprises.idEnt AND idStage='$_GET[stage]'";
        // Exécution de la requète
        $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: " . mysqli_error($dblink));
        // Remplissage du tableau
        $data = mysqli_fetch_assoc($result);

        $destinataire = $data['mailContactStage'];
        $typemail = "stage";
        $sujet = "Stage: ";
        $sujet .= $data['nomStage'];
    }
    if (isset($_GET['ent'])) {
        // Requète SQL
        $query = "SELECT * FROM entreprises WHERE idEnt='$_GET[ent]'";
        // Exécution de la requète
        $result = mysqli_query($dblink, $query) or die("Erreur lors de la requète SQL: " . mysqli_error($dblink));
        // Remplissage du tableau
        $data = mysqli_fetch_assoc($result);
        $typemail = "ent";
        $destinataire = $data['mailEnt'];
        $sujet = "Demande de stage";
    }

    ?>
    <h1>Envoyer un mail</h1>
    <form method="post" name="contact" action="mail">
        <div class="col_13 float_l">
            <input type="hidden" name="envoi_mail"/>
            <input type="hidden" name="destinataire" value="<?php if (isset ($destinataire)) echo $destinataire ?>"/>
            <input type="hidden" name="typemail" value="<?php if (isset ($typemail)) echo $typemail ?>"/>

            <label for="$nom_expediteur">Expediteur:</label>
            <input type="text" id="nom_expediteur" name="nom_expediteur" maxlength="100" value="<?php if (isset ($nom_disp) && isset($nom_disp2)) echo $nom_disp . ' ' . $nom_disp2; ?>"/>

            <div class="cleaner h10"></div>

            <label for="dest">Destinataire:</label>
            <input type="text" id="dest" name="dest" maxlength="100"
                   placeholder="<?php if (isset ($destinataire)) echo $destinataire ?>" <?php if ($_SESSION['connected'] !== "admin") echo 'disabled="disabled"'; ?> />

            <div class="cleaner h10"></div>

            <input type="checkbox" name="cv" id="cv" value="1" <?php if (file_exists($cv)) {
                echo 'checked="checked"';
            } else {
                echo 'disabled="disabled"';
            }; ?> />
            <label class="checkbox" for="cv" <?php if (!file_exists($cv)) { ?> onclick="alert('Vous n\'avez pas de CV !\r\n Vous pouvez en enregistrer un avec l\'longlet Compte');" <?php } ?> >Joindre
                mon CV</label>
        </div>
        <div class="col_23 float_r">
            <label for="sujet">Sujet:</label>
            <input type="text" name="sujet" id="sujet" maxlength="150" value="<?php if (isset ($sujet)) echo $sujet ?>"/>

            <div class="cleaner h10"></div>

            <label for="message">Message:</label>
            <textarea id="mail" name="message" maxlength="1000"></textarea>

            <div class="cleaner h20"></div>

            <input type="submit" value="Envoyer" id="submit" name="submit" class="big_button"/>
        </div>
    </form>
<?php
}
include('all.footer.php');
?>