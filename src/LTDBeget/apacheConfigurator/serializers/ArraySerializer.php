<?php
/**
 * @author: Viskov Sergey
 * @date: 30.04.15
 * @time: 12:57
 */


namespace LTDBeget\apacheConfigurator\serializers;


use LTDBeget\apacheConfigurator\ConfigurationFile;
use LTDBeget\apacheConfigurator\directives\DirectivePath;
use LTDBeget\apacheConfigurator\interfaces\iConfigurationFile;
use LTDBeget\apacheConfigurator\interfaces\iDirective;
use LTDBeget\apacheConfigurator\interfaces\iSerializer;

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

        $leafs = self::getInstance()->explodeOnLeafs($configuration);
        $paths = self::getInstance()->explodeOnPath($leafs);


        foreach($paths as $key => $path) {
            $fullPath = new DirectivePath($path);
//            print_r($fullPath);
//            $parent = $fullPath->getParentPath();
//            $likeFull = $parent->makeChildDirectivePath($fullPath->getDirectiveType(), $fullPath->getDirectiveValue());
//            print_r($likeFull);
//            echo "compareresult = ".$fullPath->comparePath($likeFull)."\n";
//            echo $fullPath->getDirectiveType()."\n";
//            echo $fullPath->getDirectiveValue()."\n";
//            print_r($fullPath->getParentPath()->getPath());
            $configurationFile->addDirective($fullPath->getDirectiveType(), $fullPath->getDirectiveValue(), $fullPath->getParentPath());
        }

        return $configurationFile;
    }

    /**
     * @param array $paths
     * @return array
     */
    protected function explodeOnPath(array $paths)
    {
        $formatPaths = [];
        foreach($paths as $path) {
            unset($innerDirective);
            do {
                if(!isset($innerDirective)) {
                    $innerDirective = [
                        "directive"      => $path["directive"],
                        "value"          => $path["value"]
                    ];
                }

                if(isset($path["parent"])) {
                    $innerDirective = [
                        "directive"      => $path["parent"]["directive"],
                        "value"          => $path["parent"]["value"],
                        "innerDirective" => $innerDirective
                    ];
                    $path = $path["parent"];
                }

            } while(isset($path["parent"]));
            $formatPaths[] = $innerDirective;
        }

        return $formatPaths;
    }

    protected function explodeOnLeafs($directives, $parentKey = null, &$paths = [])
    {
        foreach($directives as $key => $directive) {
            $childKey = is_null($parentKey)?$key:$parentKey.$key;
            $path = [
                "directive"      => $directive["directive"],
                "value"          => $directive["value"]
            ];
            if(!is_null($parentKey)) {
                $path["parent"] = $paths[$parentKey];
            }
            $paths[$childKey] = $path;
            if(isset($directive["innerDirective"])) {
                self::explodeOnLeafs($directive["innerDirective"], $childKey, $paths);
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