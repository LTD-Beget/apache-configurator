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

class CheckCaseOnly extends Directive
{
    /**
     * Return link to full description of apache directive
     * @return String
     */
    public static function getApacheDocLink()
    {
        return Directive::$apacheSite."/docs/2.4/mod/mod_speling.html#checkcaseonly";
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public static function getModule()
    {
        return "mod_speling";
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public static function getDescription()
    {
        return "Limits the action of the speling module to case corrections";
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public static function getSyntax()
    {
        return 'CheckCaseOnly on|off';
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
            Directory::getFullName(),
            ConfigurationFile::HTACCESS,
        ];
    }

}