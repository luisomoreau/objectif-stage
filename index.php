<?php
$loginreq=0;
include('all.header.php');
echo "test";
?>
            <h1>Bienvenue sur la plateforme de Stage de l'UNC</h1>
        	<div class="col col_23">
            <div class="justify">
            	<img src="images/unc_agora.jpg" alt="image" class="img_frame" />
                <p><em>Université de la Nouvelle-Calédonie</em></p>
                <p>L’UNC est une jeune université présente en Nouvelle-Calédonie depuis sa création en 1987 sous la dénomination « Université Française du Pacifique », en référence à l’entité unique constituée alors avec la Polynésie Française. En 1999, elle devient l’Université de la Nouvelle-Calédonie.</p>
                <p><em>La licence Science Pour l'Ingénieur mention Informatique</em></p>
                <p>La licence de Sciences, Technologie, Santé mention Sciences pour l’Ingénieur est une formation à caractère professionnel adaptée aux étudiants souhaitant accéder au marché de l’emploi avec un diplôme bac+3. Cette licence comporte deux parcours « Métallurgie énergétique et Génie des Procédés » et « Informatique ». Le premier parcours a pour finalité de former des opérateurs de procédés métallurgiques et des techniciens supérieurs de mesures physiques. Le deuxième parcours a un objectif double : former des techniciens supérieurs en informatique, et permettre à certains étudiants une poursuite d’étude en master ou en école d’ingénieur (grâce aux options).</p>
                <div class="cleaner h10"></div>
                <p>Plus d'informations sur la licence <a target="_blank" href="http://www.univ-nc.nc/sites/www.univ-nc.nc/files/fiche_formation_licence_sciences_pour_lingenieur_parcours_informatique.pdf">SPI Informatique</a>.</p>
                <p>Retrouvez tous les documents utiles dans l'onglet <a href="/documents">Documents</a>.</p>
                <div class="cleaner h40"></div>
             </div>
             </div>
             <div class="col col_13 no_margin_right">
<?php
if ($logged === 0) {
?>
            	<h3>Inscriptions</h3>
                <form class="inscriptions" action="inscription" method="POST">
                    <input type="hidden" name="typeinscription" value="etud"/>
                    <button class="big_button" type="submit">Etudiants</button>
                </form>
                <form class="inscriptions" action="inscription" method="POST">
                    <input type="hidden" name="typeinscription" value="ent"/>
                    <button class="big_button" type="submit">Entreprises</button>
                </form>
<?php
} else {
    if ($_SESSION[type]== "entreprises") {
?>			
                <h3>Accès rapide</h3>
                <a href="depotstage"><button class="big_button" >Déposer un stage</button></a>
                <a href="messtages"><button class="big_button" >Mes stages</button></a>
<?php
    } else if ($_SESSION[type] == "etudiants") {
?>
                <h3>Accès rapide</h3>
                <a href="listestages"><button class="big_button" >Liste des stages</button></a>
                <a href="listeentreprises"><button class="big_button" >Liste des entreprises</button></a>
<?php
        } else {
?>
                <h3>Accès rapide</h3>
                <a href="listeentreprises"><button class="big_button" >Liste des entreprises</button></a>
                <a href="listeetudiants"><button class="big_button" >Liste des étudiants</button></a>
                <a href="listestages"><button class="big_button" >Liste des stages</button></a>   
                <!--<a href="statistiques"><button class="big_button" >Statistiques</button></a>-->        
<?php
        }
}
?>
            </div>
            <div class="cleaner"></div>
<?php
include('all.footer.php');
?>