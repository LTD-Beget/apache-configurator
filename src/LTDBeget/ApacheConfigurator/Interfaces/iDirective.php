<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 15:58
 */


namespace LTDBeget\ApacheConfigurator\Interfaces;


interface iDirective
{
    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule();

    /**
     * Value of Apache directive
     * @return String
     */
    public function getValue();

    /**
     * set value for directive
     * @param String $value
     * @return mixed
     */
    public function setValue($value);

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax();

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription();

    /**
     * Return link to full description of apache directive
     * @return String
     */
    public function getApacheDocLink();

    /**
     * is this directive can include inner directives
     * @return boolean
     */
    public function isSection();
}