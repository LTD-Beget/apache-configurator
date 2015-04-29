<?php
/**
 * @author: Viskov
 * @date: 28.04.15
 * @time: 15:40
 */


namespace LTDBeget\ApacheConfigurator;


use LTDBeget\ApacheConfigurator\Directives\DirectivePath;
use LTDBeget\ApacheConfigurator\Interfaces\iConfigurationFile;
use LTDBeget\ApacheConfigurator\Interfaces\iSerializer;

class BaseSerializer implements iSerializer
{

    /**
     * @param String $fileType
     * @param String $configuration
     * @return ConfigurationFile
     */
    public static function fromJson($fileType, $configuration)
    {
        self::fromArray($fileType, json_decode($configuration, true));
    }

    /**
     * @param ConfigurationFile $configurationFile
     * @return String
     */
    public static function toJson(ConfigurationFile $configurationFile)
    {
        return json_decode(self::toArray($configurationFile));
    }

    /**
     * @param String $fileType
     * @param Array $configuration
     * @return ConfigurationFile
     */
    public static function fromArray($fileType, array $configuration)
    {
        $configurationFile = new ConfigurationFile($fileType);
        $paths = self::explodeOnPath($configuration);
        foreach($paths as $path) {
            $configurationFile->addDirective($path);
        }
        return $configurationFile;
    }

    /**
     * @param ConfigurationFile $configurationFile
     * @return Array
     */
    public static function toArray(ConfigurationFile $configurationFile)
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @param String $fileType
     * @param String $configuration
     * @return iConfigurationFile
     */
    public static function fromPlain($fileType, $configuration)
    {
        // TODO: deferred method. Realize when everything ready
    }

    /**
     * @param ConfigurationFile $configurationFile
     * @return String
     */
    public static function toPlain(ConfigurationFile $configurationFile)
    {
        // TODO: Implement toPlain() method.
    }

    protected static function explodeOnPath(array $directives, &$paths = [])
    {
        foreach($directives as $directive) {
            if(isset($directive["innerDirective"]) and is_array($directive["innerDirective"]) and count($directive["innerDirective"])) {
                self::explodeOnPath($directive["innerDirective"], $paths);
            } else {
                unset($directive["innerDirective"]);
                $path = new DirectivePath($directive);
                array_push($paths, $path);
            }
        }
        return $paths;
    }
}