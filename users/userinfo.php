<?php
/**
 * Created by PhpStorm.
 * User: lmoreau
 * Date: 12/06/14
 * Time: 14:26
 */

//require('../CAS.php');
require('ldap.class.php');

/**
 * Récupération des informations de l'utilisateur selon son identifiant CAS
 */

//$user = phpCAS::getUser();

function get_ldap_datas($user){
    //Appel à la classe ldap.class.php
    $ldap = new ldap();
    //connexion
    $ldap->connect();
    //récupération de l'identifiant de l'utilisateur
    $entry = $ldap->get_user($user->init);

    //Si aucune entrée ldap n'a été trouvé on stop le bordel
        if(!$entry) return;

    //Récupérer le mail de l'utilisateur
        $mail = $entry['mail'][0];

    //Récupérer le nom + prénom de l'utilisateur
        $name = $entry['displayname'][0];

    //Récupérer le statut
        $statut = $entry['edupersonaffiliation'];

    return $mail;
}


