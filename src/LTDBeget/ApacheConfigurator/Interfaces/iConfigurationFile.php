<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 15:57
 */


namespace LTDBeget\ApacheConfigurator\Interfaces;


interface iConfigurationFile
{
    /**
     * @param iDirectivePath $directivePath
     * @return void
     */
    public function addDirective(iDirectivePath $directivePath);

    /**
     * @param iDirectivePath $directivePath
     * @param String $value
     * @return void
     */
    public function changeDirective(iDirectivePath $directivePath, $value);

    /**
     * @param iDirectivePath $directivePath
     * @return void
     */
    public function removeDirective(iDirectivePath $directivePath);

    /**
     * @return string type of current configurationFile "serverConfig", "htaccess"
     */
    public function getFileType();
}