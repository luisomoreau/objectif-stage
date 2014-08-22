<?php
include('all.header.php');
if (isset($_POST['identifiant']) && isset($_POST['mdp'])) {
    $mysqli = new mysqli($sqlserver, $sqlid, $sqlpwd, $sqldb);

    $mdp = hash('sha512', $salt . $_POST['mdp']);
    if (!($stmt = $mysqli->prepare('SELECT COUNT(*), idEnt, nomEnt, valideEnt FROM entreprises WHERE mailEnt=? AND mdpEnt=?'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    $stmt->bind_param('ss', $_POST['identifiant'], $mdp);
    if (!($stmt->execute())) {
        echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
        echo "Erreur, le mail est déjà utilisé";
    }
    $stmt->bind_result($exist, $id, $nom, $valide);
    $stmt->fetch();
    $stmt->close();
    if ($exist) {
        if (!$valide) {
            header('location: ./loginent?nonvalide');
        } else {
            $_SESSION['identifiant'] = $nom;
            $_SESSION['id'] = $id;
            $_SESSION["connected"] = "ent";
            header('location: ./');
            die();
        }
    } else {
        header('location: ./loginent?erreur');
    }
} else {
    ?>
    <div class="row">
        <div class="large-12 columns">
            <h1>Connexion entreprise</h1>
        </div>
    </div>
    <form method="post">
        <div class="row">
            <div class="small-12 large-6 large-centered columns">
                <?php
                    if (isset($_GET['erreur'])) {
                        echo '<h4><span style="color:red">Erreur, identifiants incorrects !</span></h4>';
                        echo '<a class="right" href="./rstpwd">Mot de passe oublié ?</a><br><br>';
                    }
                    if (isset($_GET['nonvalide'])) {
                        echo '<h4><span style="color:red">Erreur, votre compte n\'a pas encore été validé.</span></h4>';
                    }
                ?>
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