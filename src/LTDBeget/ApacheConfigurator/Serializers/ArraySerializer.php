<?php
/**
 * @author: Viskov Sergey
 * @date: 30.04.15
 * @time: 12:57
 */


namespace LTDBeget\ApacheConfigurator\Serializers;


use LTDBeget\ApacheConfigurator\ConfigurationFile;
use LTDBeget\ApacheConfigurator\Directives\DirectivePath;
use LTDBeget\ApacheConfigurator\Interfaces\iConfigurationFile;
use LTDBeget\ApacheConfigurator\Interfaces\iSerializer;

class ArraySerializer implements iSerializer {

    /**
     *
     * @param iConfigurationFile $configurationFile
     * @return array|string
     */
    public static function serialize(iConfigurationFile $configurationFile)
    {
        // TODO: Implement serialize() method.
    }

    /**
     * @param String $fileType
     * @param Array $configuration
     * @return ConfigurationFile
     */
    public static function deserialize($fileType, $configuration)
    {
        $configurationFile = new ConfigurationFile($fileType);
        $paths = self::explodeOnPath($configuration);

        foreach($paths as $path) {
            $configurationFile->addDirective($path);
        }
        return $configurationFile;
    }

    /**
     * Explode array of Right type on plain array of path
     * @param array $directives
     * @param array $paths
     * @param null $contextPath
     * @return array
     */
    protected static function explodeOnPath(array $directives, &$paths = [], $contextPath = null)
    {
        foreach($directives as $directive) {
            if(isset($directive["innerDirective"]) and is_array($directive["innerDirective"]) and count($directive["innerDirective"])) {
                $contextPath = [
                    "directive"      => $directive["directive"],
                    "value"          => $directive["value"],
                    "innerDirective" => []
                ];

                self::explodeOnPath($directive["innerDirective"], $paths, $contextPath);
            } else {
                if(is_null($contextPath)) {
                    unset($directive["innerDirective"]);
                    $path = new DirectivePath($directive);
                } else {
                    $contextPath["innerDirective"] = $directive;
                    $path = new DirectivePath($contextPath);
                }

                $paths[] = $path;
            }
        }

        return $paths;
    }
}