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

class ISAPIReadAheadBuffer extends Directive
{
    /**
     * Return link to full description of apache directive
     * @return String
     */
    public function getApacheDocLink()
    {
        return $this->apacheSite."/docs/2.4/mod/mod_isapi.html#isapireadaheadbuffer";
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule()
    {
        return "mod_isapi";
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription()
    {
        return "Size of the Read Ahead Buffer sent to ISAPI extensions";
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax()
    {
        return 'ISAPIReadAheadBuffer size';
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