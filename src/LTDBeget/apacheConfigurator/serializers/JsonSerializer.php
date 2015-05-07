<?php
/**
 * @author: Viskov Sergey
 * @date: 30.04.15
 * @time: 12:56
 */


namespace LTDBeget\apacheConfigurator\serializers;


use LTDBeget\apacheConfigurator\interfaces\iConfigurationFile;
use LTDBeget\apacheConfigurator\interfaces\iSerializer;

class JsonSerializer implements iSerializer
{

    /**
     *
     * @param iConfigurationFile $configurationFile
     * @return array|string
     */
    public static function serialize(iConfigurationFile $configurationFile)
    {
        return json_encode(ArraySerializer::serialize($configurationFile));
    }

    /**
     * @param String $fileType
     * @param String|array $configuration
     * @return iConfigurationFile
     */
    public static function deserialize($fileType, $configuration)
    {
        ArraySerializer::deserialize($fileType, json_decode($configuration, true));
    }
}