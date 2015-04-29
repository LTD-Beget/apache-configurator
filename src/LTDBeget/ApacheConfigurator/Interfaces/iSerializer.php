<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 15:59
 */


namespace LTDBeget\ApacheConfigurator\Interfaces;


use LTDBeget\ApacheConfigurator\ConfigurationFile;

interface iSerializer
{
    /**
     * @param String $fileType
     * @param String $configuration
     * @return ConfigurationFile
     */
    public static function fromJson($fileType, $configuration);

    /**
     * @param ConfigurationFile $configurationFile
     * @return String
     */
    public static function toJson(ConfigurationFile $configurationFile);

    /**
     * @param String $fileType
     * @param Array $configuration
     * @return ConfigurationFile
     */
    public static function fromArray($fileType, array $configuration);

    /**
     * @param ConfigurationFile $configurationFile
     * @return Array
     */
    public static function toArray(ConfigurationFile $configurationFile);

    /**
     * @param String $fileType
     * @param String $configuration
     * @return ConfigurationFile
     */
    public static function fromPlain($fileType, $configuration);

    /**
     * @param ConfigurationFile $configurationFile
     * @return String
     */
    public static function toPlain(ConfigurationFile $configurationFile);
}