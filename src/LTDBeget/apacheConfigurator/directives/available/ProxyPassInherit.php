<?php
/**
 * Automatically generated
 *
 * @author: Viskov Sergey
 * @date: 07.05.15
 */


namespace LTDBeget\apacheConfigurator\directives\available;


use LTDBeget\apacheConfigurator\ConfigurationFile;
use LTDBeget\apacheConfigurator\directives\Directive;

class ProxyPassInherit extends Directive
{
    /**
     * Return link to full description of apache directive
     * @return String
     */
    public static function getApacheDocLink()
    {
        return Directive::$apacheSite."/docs/2.4/mod/mod_proxy.html#proxypassinherit";
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public static function getModule()
    {
        return "mod_proxy";
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public static function getDescription()
    {
        return "Inherit ProxyPass directives defined from the main server";
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public static function getSyntax()
    {
        return 'ProxyPassInherit On|Off';
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
            ConfigurationFile::SERVER_CONFIG,
            VirtualHost::getFullName(),
        ];
    }

}