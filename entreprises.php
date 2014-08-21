<?php
//include('logincheck2.php');
include("all.header.php");
echo '<section class="row">
            <div class="small-12 columns">
                <h1>Bienvenue sur l\'espace dédié aux entreprises</h1>
            </div>
         </section>';
if (!(isset($_SESSION['connected']))) {
    echo '
    <section class="row">
        <div class="large-6 columns">
            <a href="./loginent" class="large button expand">Se connecter</a>
        </div>
        <div class="large-6 columns">
            <a href="./inscription" class="large button expand">S\'inscrire</a>
        </div>
    </section>';
}

?>
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
include("all.footer.php");