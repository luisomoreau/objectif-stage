<?php
include('all.header.php');
if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);
    $mdp = hash('sha512', $salt . $_POST['mdp']);
    if (!($stmt = $mysqli->prepare('SELECT COUNT(*), idAdmin, nomAdmin, prenomAdmin, mailAdmin FROM administrateurs WHERE mailAdmin=? AND mdpAdmin=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('ss', $_POST['identifiant'], $mdp);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "Erreur, le mail est déjà utilisé";
    }
    $stmt->bind_result($exist, $id, $nom, $prenom, $mailAdmin);
    $stmt->fetch();
    $stmt->close();
    if ($exist) {
        echo $id;
        $_SESSION['identifiant'] = $nom." ".$prenom;
        $_SESSION['id'] = $id;
        $_SESSION['mail'] = $mailAdmin;
        $_SESSION["connected"] = "admin";
        $_SESSION["statut"] = "admin";
        header('location: ./');
        die();
    } else {
        header('location: ./admin');
    }
} else {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <h1>Connexion administrateur</h1>
        </div>
    </div>
    <form method="post">
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input placeholder="Votre identifiant (E-mail)" type="email" name="identifiant" id="identifiant" maxlength="100" required="required"/>
            </div>
        </div>
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input placeholder="Mot de passe" type="password" name="mdp" id="mdp" maxlength="25" required="required"/>
            </div>
        </div>
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <input class="large button expand" id="envoyer" name="submit" type="submit" value="Se connecter"/>
            </div>
        </div>
    </form>
<?php
}
include('all.footer.php');