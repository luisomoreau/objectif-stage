<?php
/**
 * Created by PhpStorm.
 * User: lmoreau
 * Date: 12/06/14
 * Time: 16:40
 */
//variables
$connexion = FALSE;
$hostname = 'ldap://ldap.univ-nc.nc';
$port = 636;
$dn = 'dc=univ-nc, dc=nc';
echo "Connexion au serveur <br />";
$connexion=ldap_connect($hostname,$port);

// on teste : le serveur LDAP est-il trouvé ?
if ($connexion)
    echo "Le résultat de connexion est ".$connexion ."<br />";
else
    die("connexion impossible au serveur LDAP");

/* 2ème étape : on effectue une liaison au serveur, ici de type "anonyme"
 * pour une recherche permise par un accès en lecture seule */

// On dit qu'on utilise LDAP V3, sinon la V2 par défaut est utilisé
// et le bind ne passe pas.
if (ldap_set_option($connexion, LDAP_OPT_PROTOCOL_VERSION, 3)) {
    echo "Utilisation de LDAPv3 \n";
} else {
    echo "Impossible d'utiliser LDAP V3\n";
    exit;  }


$bindServerLDAP=ldap_bind($connexion);

print ("Liaison au serveur : ". ldap_error($connexion)."\n");
// en cas de succès de la liaison, renvoie Vrai
if ($bindServerLDAP)
    echo "Le résultat de connexion est $bindServerLDAP <br />";
else
    die("Liaison impossible au serveur ldap ...");

/* 3ème étape : on effectue une recherche anonyme, avec le dn de base,
par exemple, sur tous les noms commençant par B */

echo "Recherche suivant le filtre (uid=nbrengard) <br />";
$query = "uid=nbrengard";
$result=ldap_search($connexion, $dn, $query);
echo "Le résultat de la recherche est $result <br />";

echo "Le nombre d'entrées retournées est ";
echo ldap_count_entries($connexion,$result)."<p />";
echo "Lecture de ces entrées ....<p />";
$info = ldap_get_entries($connexion, $result);
echo "Données pour ".$info["count"]." entrées:<p />";

for ($i=0; $i < $info["count"]; $i++) {
    echo "dn est : ". $info[$i]["dn"] ."<br />";
    echo "Nom complet : ". $info[$i]["cn"][0] ."<br />";
    echo "Email : ". $info[$i]["mail"][0] ."<br />";
    echo "Numéro de téléphone : ". $info[$i]["telephonenumber"][0] ."<p />";
    echo "Année :". $info[$i]["supannetucursusanne"][0]. "<p />";
}
/* 4ème étape : clôture de la session  */
echo "Fermeture de la connexion";
ldap_close($connexion);
