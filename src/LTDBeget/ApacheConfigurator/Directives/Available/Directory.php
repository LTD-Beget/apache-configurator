<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 18:43
 */


namespace LTDBeget\ApacheConfigurator\Directives\Available;


use LTDBeget\ApacheConfigurator\ConfigurationFile;
use LTDBeget\ApacheConfigurator\Directives\Directive;
use LTDBeget\ApacheConfigurator\Interfaces\iDirective;

class Directory extends Directive
{
    /**
     * @var String
     */
    protected $module = "core";

    /**
     * Text description of apache directive
     * @var String
     */
    protected $description = "Enclose a group of directives that apply only to the named file-system directory, sub-directories, and their contents.";

    /**
     * Example Apache directive syntax
     * @var String
     */
    protected $syntax = '<Directory "directory-path"> ... </Directory>';

    /**
     * link to full description of apache directive
     * @var String
     */
    protected $apacheDocLink = "/docs/2.4/mod/core.html#directory";

    /**
     * @var Boolean
     */
    protected $isSection = true;

    /**
     * @var iDirective[]|null
     */
    protected $innerDirectives = [];

    /**
     * Set allowed context of concrete directive
     */
    protected function setAllowedContext()
    {
        $this->allowedContext = [
            VirtualHost::className(),
            ConfigurationFile::SERVER_CONFIG
        ];
    }
}