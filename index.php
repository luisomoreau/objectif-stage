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

    echo '<section class="row">
            <div class="small-12 columns">
                <h1>Bienvenue sur la plateforme de Stage de l\'UNC</h1>
            </div>
         </section>';
    if (!(isset($_SESSION['connected']))) {
        echo '
    <section class="row">
        <div class="large-6 column">
            <a href="./loginetud" class="large button expand">Vous êtes un étudiant</a>
        </div>
        <div class="large-6 column">
            <a href="./entreprises" class="large button expand">Vous êtes une entreprise</a>
        </div>
    </section>';
    } else {
        if ($_SESSION["connected"]=="etud") {
            echo '
    <section class="row">
        <div class="large-6 column">
            <a href="./listestages" class="large button expand">Accédez à la liste des offres</a>
        </div>
        <div class="large-6 column">
            <a href="./listeentreprises" class="large button expand">Accédez à la liste des entreprises</a>
        </div>
    </section>';
        }
    }

    echo '    <section class="row">
        <div class="large-12">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc congue sem lorem, id elementum libero fringilla sit amet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec at suscipit purus. Etiam ut eleifend velit. Morbi molestie, leo ac condimentum tempus, neque ipsum fringilla neque, nec ultrices libero ante vel est. Donec purus tellus, suscipit sit amet erat eget, posuere auctor turpis. Nam aliquam, turpis ac adipiscing placerat, nibh enim posuere nulla, ac molestie orci erat vitae elit. Integer at tellus nec nunc euismod blandit. Sed imperdiet ac sapien lobortis suscipit. Nullam vehicula et erat ac mollis. Etiam nec consectetur orci, nec hendrerit ante. Nullam auctor at odio in convallis. Proin accumsan nibh sit amet nisi vulputate, ac sodales tortor mattis.
            </p>
            <p>
                Sed imperdiet, turpis et consequat commodo, metus ipsum lacinia odio, et scelerisque elit libero vitae lacus. Aenean bibendum purus vitae tempor iaculis. Fusce auctor tristique arcu, consectetur malesuada erat pulvinar et. Nulla eget rhoncus dui, ac ornare urna. Etiam gravida tristique diam at luctus. Curabitur mattis, justo vel sollicitudin facilisis, lectus mi vulputate augue, non commodo ipsum nibh nec nisi. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla eget nisl quis nisi luctus condimentum. Aenean nec ligula turpis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus convallis vel dui in semper.
            </p>
            <p>
                Vivamus auctor urna ut laoreet scelerisque. Quisque ac nibh enim. Aliquam vel consequat dolor, condimentum ultrices est. Duis viverra turpis risus. Phasellus et diam nisl. Morbi posuere laoreet sapien, ac lobortis neque pulvinar nec. In hac habitasse platea dictumst. Integer ut neque vel leo commodo consequat vitae eget dolor. Etiam sit amet turpis adipiscing nulla sagittis adipiscing non at sem. Aliquam posuere, justo nec commodo interdum, magna erat euismod diam, eget egestas purus nisi a nisl. Nullam vestibulum magna quam, vitae vulputate mi lacinia quis. In in mauris at libero varius laoreet.
            </p>
            <p>
                Nullam enim erat, mollis a dictum pellentesque, tincidunt ac ligula. Nulla vitae bibendum velit. In nec diam leo. Suspendisse iaculis enim ut nunc mattis, vitae laoreet lorem tempor. Sed non dapibus quam. Nulla vel dolor venenatis, commodo est eget, pretium metus. Ut ut arcu tortor. Cras sed sem a neque pharetra accumsan. Donec quis dolor tellus. Cras mollis tortor arcu, nec vestibulum nibh tincidunt sit amet.
            </p>
            <p>
                Curabitur eros mauris, convallis id posuere eu, placerat quis felis. Maecenas sit amet purus blandit, euismod felis sed, pulvinar elit. Donec turpis enim, tincidunt et leo at, fermentum pharetra massa. In quis sapien est. Praesent condimentum ultricies magna, non eleifend ligula ultricies ac. Donec ultrices dui pellentesque congue feugiat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nullam ultricies interdum odio vel placerat.
            </p>
        </div>
    </section>';
    include("all.footer.php");
?>