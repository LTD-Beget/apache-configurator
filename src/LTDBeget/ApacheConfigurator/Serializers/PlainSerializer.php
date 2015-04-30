<?php
/**
 * @author: Viskov Sergey
 * @date: 30.04.15
 * @time: 12:57
 */


namespace LTDBeget\ApacheConfigurator\Serializers;


use LTDBeget\ApacheConfigurator\Interfaces\iConfigurationFile;
use LTDBeget\ApacheConfigurator\Interfaces\iSerializer;

class PlainSerializer implements iSerializer {

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
     * @param String|array $configuration
     * @return iConfigurationFile
     */
    public static function deserialize($fileType, $configuration)
    {
        // TODO: Implement deserialize() method.
    }
}