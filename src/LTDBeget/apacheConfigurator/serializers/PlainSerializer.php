<?php
/**
 * @author: Viskov Sergey
 * @date: 30.04.15
 * @time: 12:57
 */


namespace LTDBeget\apacheConfigurator\serializers;


use LTDBeget\apacheConfigurator\interfaces\iConfigurationFile;
use LTDBeget\apacheConfigurator\interfaces\iDirective;
use LTDBeget\apacheConfigurator\interfaces\iSerializer;

class PlainSerializer implements iSerializer
{
    protected static $instance = null;

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    /**
     * singleton getter
     * @return PlainSerializer
     */
    static protected function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     *
     * @param iConfigurationFile $configurationFile
     * @return array|string
     */
    public static function serialize(iConfigurationFile $configurationFile)
    {
        $configurationPlain = "";

        foreach($configurationFile->getInnerDirectives() as $directive) {
            $configurationPlain .= self::getInstance()->directiveToPlain($directive);
        }

        return $configurationPlain;
    }

    /**
     * @param String $fileType
     * @param String|array $configuration
     * @return iConfigurationFile
     */
    public static function deserialize($fileType, $configuration)
    {
        // TODO: Implement deserialize() method.
    }

    /**
     * converts directive object to plain with its inner directives as plain too
     * @param iDirective $directive
     * @param int $nestLevel
     * @return array
     */
    protected function directiveToPlain(iDirective $directive, $nestLevel = 0)
    {
        $tabulation     = $nestLevel > 0 ? str_repeat("  ", $nestLevel) : "";

        if ($directive->isSection()) {
            $directivePlain = $tabulation . "<{$directive->getName()} {$directive->getValue()}>\n";
            foreach ($directive->getInnerDirectives() as $innerDirective) {
                $directivePlain .= $this->directiveToPlain($innerDirective, $nestLevel + 1);
            }
            $directivePlain .= $tabulation . "</{$directive->getName()}>\n";
        } else {
            $directivePlain = $tabulation . "{$directive->getName()} {$directive->getValue()}\n";
        }

        return $directivePlain;
    }
}