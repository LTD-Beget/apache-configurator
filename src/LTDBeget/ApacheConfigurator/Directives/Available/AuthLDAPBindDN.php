<?php
/**
 * Automatically generated
 *
 * @author: Viskov Sergey
 * @date: 07.05.15
 */


namespace LTDBeget\ApacheConfigurator\Directives\Available;


use LTDBeget\ApacheConfigurator\ConfigurationFile;
use LTDBeget\ApacheConfigurator\Directives\Directive;

class AuthLDAPBindDN extends Directive
{
    /**
     * Return link to full description of apache directive
     * @return String
     */
    public function getApacheDocLink()
    {
        return $this->apacheSite."/docs/2.4/mod/mod_authnz_ldap.html#authldapbinddn";
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule()
    {
        return "mod_authnz_ldap";
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription()
    {
        return "Optional DN to use in binding to the LDAP server";
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax()
    {
        return 'AuthLDAPBindDN <em>distinguished-name</em>';
    }

    /**
     * is this directive can include inner directives
     * @return boolean
     */
    public function isSection()
    {
        return false;
    }

    /**
     * Set allowed context of concrete directive
     */
    protected function setAllowedContext()
    {
        $this->allowedContext = [
            Directory::getFullName(),
            ConfigurationFile::HTACCESS,
        ];
    }

}