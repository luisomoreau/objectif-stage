<?php
include("all.header.php");
// Check si logged via CAS
if (!isset($_SESSION['connected'])) {
    // Load the settings from the central config file
    require_once 'config.php';
    // Load the CAS lib
    require_once 'CAS.php';
    // Initialize phpCAS
    phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context);
    // For quick testing you can disable SSL validation of the CAS server.
    // THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
    // VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
    phpCAS::setNoCasServerValidation();
    $auth = phpCAS::checkAuthentication();
    if ($auth) {
        casLogin();
    }
}

echo '<div class="row">
            <div class="small-12 columns">
                <h1>Bienvenue sur la plateforme de stage de l\'UNC !</h1>
            </div>
         </div>';
if (!(isset($_SESSION['connected']))) {
    ?>

    <div class="row">
        <div class="large-6 column">
            <a href="./loginetud" class="large button expand">Vous êtes un étudiant</a>
        </div>
        <div class="large-6 column">
            <a href="./entreprises" class="large button expand">Vous êtes une entreprise</a>
        </div>
    </div>
    <div class="row">
        <div class="large-12 columns">
            <h3>Vous êtes un étudiant :</h3>

            <p>
                En vous inscrivant, vous aurez accès en ligne à l'ensemble des offres de stage proposées par les entreprises. Vous pourrez y répondre directement et être accompagnés dans vos démarches
                par le bureau d’aide à l’insertion professionnelle (BAIP).
            </p>
        </div>
    </div>
    <div class="row">
        <div class="large- columns">
            <h3>Vous êtes une entreprise</h3>
            <p>
                Vous recherchez des compétences adaptées à vos besoins ? N’hésitez pas à déposer vos offres de stages directement en ligne à destination des étudiants de l'Université de la
                Nouvelle-Calédonie.
            </p>

            <p>
                Vos offres seront systématiquement relues et accompagnées par nos soins. Leur diffusion sera renforcée vers les étudiants des formations les plus en rapport avec vos attentes.
            </p>

            <p>
                Suite à votre inscription, vous recevrez un mail de confirmation dès que votre compte aura été validé. Vous pourrez ensuite vous connecter.
            </p>

            <p>
                Le bureau d’aide à l’insertion professionnelle (BAIP) se tient à votre disposition pour vous accompagner dans vos démarches vers et avec nos étudiants.
            </p>

            <p class="text-right">
                Contact BAIP : 290 191 ou baip@univ-nc.nc
            </p>
        </div>
    </div>
<?php
} else {
    if ($_SESSION["connected"] == "etud") {
        echo '
    <div class="row">
        <div class="large-6 column">
            <a href="./listestages" class="large button expand">Accédez à la liste des offres</a>
        </div>
        <div class="large-6 column">
            <a href="./listeentreprises" class="large button expand">Accédez à la liste des entreprises</a>
        </div>
    </div>';
    } else {
        if ($_SESSION["connected"] == "ent") {
            ?>
            <div class="row">
                <div class="large- columns">
                    <p>
                        Vous recherchez des compétences adaptées à vos besoins ? N’hésitez pas à déposer vos offres de stages directement en ligne à destination des étudiants de l'Université de la
                        Nouvelle-Calédonie.
                    </p>

                    <p>
                        Vos offres seront systématiquement relues et accompagnées par nos soins. Leur diffusion sera renforcée vers les étudiants des formations les plus en rapport avec vos attentes.
                    </p>

                    <p>
                        Suite à votre inscription, vous recevrez un mail de confirmation dès que votre compte aura été validé. Vous pourrez ensuite vous connecter.
                    </p>

                    <p>
                        Le bureau d’aide à l’insertion professionnelle (BAIP) se tient à votre disposition pour vous accompagner dans vos démarches vers et avec nos étudiants.
                    </p>

                    <p class="text-right">
                        Contact BAIP : 290 191 ou baip@univ-nc.nc
                    </p>
                </div>
            </div>
        <?php
        }
    }
}


include("all.footer.php");
?>