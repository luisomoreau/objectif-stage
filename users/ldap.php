<?php
/**
 * Created by PhpStorm.
 * User: GeeKLeeT
 * Date: 13/07/14
 * Time: 02:21
 */

$connexion = FALSE;
$hostname = 'ldaps://ldap.univ-nc.nc';
$port = 389;
$dn = 'dc=univ-nc, dc=nc';
$connexion=ldap_connect($hostname);
ldap_set_option($connexion, LDAP_OPT_PROTOCOL_VERSION, 3);
$bindServerLDAP=ldap_bind($connexion);

if(isset($_GET['user'])) {
    $user = $_GET['user'];
} else {
    $user = "hlecercle";
}
$query = "uid=".$user;
$result=ldap_search($connexion, $dn, $query);

$info = ldap_get_entries($connexion, $result);
echo htmlentities(serialize($info));

ldap_close($connexion);


