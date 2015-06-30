<?php
/**
 * Automatically generated
 *
 * @author: Viskov Sergey
 * @date: 07.05.15
 */


namespace LTDBeget\apacheConfigurator\directives\available;



use LTDBeget\apacheConfigurator\directives\Directive;

class AuthzDBDRedirectQuery extends Directive
{
    /**
     * Return link to full description of apache directive
     * @return String
     */
    public function getApacheDocLink()
    {
        return Directive::$apacheSite."/docs/2.4/mod/mod_authz_dbd.html#authzdbdredirectquery";
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule()
    {
        return "mod_authz_dbd";
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription()
    {
        return "Specify a query to look up a login page for the user";
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax()
    {
        return 'AuthzDBDRedirectQuery query';
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
        ];
    }

}