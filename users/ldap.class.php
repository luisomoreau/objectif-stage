<?php
/**
 * Created by PhpStorm.
 * User: lmoreau
 * Date: 12/06/14
 * Time: 13:11
 */

class ldap {

    protected $connexion = FALSE;
    protected $hostname = 'ldaps://ldap.univ-nc.nc';
    protected $port = 389;
    protected $domain_components = 'dc=univ-nc, dc=nc';


    public function set_hostname($hostname) {
        $this->hostname = $hostname;
    }

    public function set_port($port) {
        $this->port = $port;
    }

    public function set_domain_components($domain_components) {
        $this->domain_components = $domain_components;
    }

    /**
     * Se connecter au ldap
     * @return resource
     */
    public function connect() {
        return $this->connexion = ldap_connect('ldaps://ldap.univ-nc.nc', 389);
    }

    /**
     * Retourne des entrées du dlap selon le filtre passé en paramètre
     * @param $filter
     * @param array $attributes
     * @param int $attrsonly
     * @return array|bool
     */
    public function search($filter, $attributes = array(), $attrsonly = 0) {
        $result = ldap_search($this->connexion, $this->domain_components, $filter, $attributes, $attrsonly);
        if ($result) {
            $entries = ldap_get_entries($this->connexion, $result);
            return $entries;
        }
        return FALSE;
    }

    /**
     * Retourne UNE entrée du registrer selon le filtre passé en paramètre
     * @param $filter
     * @param array $attributes
     * @param bool $clean_attributes
     * @return bool
     */
    public function get_entry($filter, $attributes = array(), $clean_attributes = TRUE) {
        $entries = $this->search($filter, $attributes);
        if (isset($entries[0])) {
            $entry = $entries[0];
            $this->clean_attributes($entry);
            return $entry;
        }
        return FALSE;
    }

    protected function clean_attributes(&$attributes) {
        foreach ($attributes as &$attribute) {
            if(isset($attribute['count'])) unset($attribute['count']);
        }
    }

    public function get_user($id, $attributes = array()) {
        return $this->get_entry("uid=$id");
    }

    public function close() {
        ldap_close($this->connexion);
    }

}