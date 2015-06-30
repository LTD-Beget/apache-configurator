<?php
/**
 * Automatically generated
 *
 * @author: Viskov Sergey
 * @date: 07.05.15
 */


namespace LTDBeget\apacheConfigurator\directives\available;



use LTDBeget\apacheConfigurator\directives\Directive;

class VHostCGIMode extends Directive
{
    /**
     * Return link to full description of apache directive
     * @return String
     */
    public function getApacheDocLink()
    {
        return Directive::$apacheSite."/docs/2.4/mod/mod_privileges.html#vhostcgimode";
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule()
    {
        return "mod_privileges";
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription()
    {
        return "Determines whether the virtualhost can run subprocesses, and the privileges available to subprocesses.";
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax()
    {
        return 'VHostCGIMode On|Off|Secure';
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
            VirtualHost::getFullName(),
        ];
    }

}