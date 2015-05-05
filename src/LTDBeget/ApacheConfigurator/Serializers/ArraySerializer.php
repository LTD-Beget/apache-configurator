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
use LTDBeget\ApacheConfigurator\Interfaces\iDirective;
use LTDBeget\ApacheConfigurator\Interfaces\iSerializer;

class ArraySerializer implements iSerializer
{
    protected static $instance = null;

    protected function __construct() {}

    protected function __clone() {}

    /**
     * singleton getter
     * @return ArraySerializer
     */
    static protected function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param iConfigurationFile $configurationFile
     * @return array|string
     */
    public static function serialize(iConfigurationFile $configurationFile)
    {
        $configurationArray = [];

        foreach($configurationFile->getInnerDirectives() as $directive) {
            $configurationArray[] = self::getInstance()->directiveToArray($directive);
        }

        return $configurationArray;
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

    /**
     * converts directive object to array with its inner directives as array too
     * @param iDirective $directive
     * @return array
     */
    protected function directiveToArray(iDirective $directive)
    {
        $directiveArray = [
            "directive" => $directive->getType(),
            "value"     => $directive->getValue()
        ];

        if($directive->isSection()) {
            $directiveArray["innerDirective"] = [];
            foreach($directive->getInnerDirectives() as $innerDirective) {
                $directiveArray["innerDirective"][] = self::getInstance()->directiveToArray($innerDirective);
            }
        }

        return $directiveArray;
    }
}