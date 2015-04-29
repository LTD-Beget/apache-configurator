<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 15:19
 */


namespace LTDBeget\ApacheConfigurator;


use LTDBeget\ApacheConfigurator\Directives\Directive;
use LTDBeget\ApacheConfigurator\Directives\Unknown;
use LTDBeget\ApacheConfigurator\Exceptions\NotAllowedContextException;
use LTDBeget\ApacheConfigurator\Exceptions\NotFoundDirectiveException;
use LTDBeget\ApacheConfigurator\Exceptions\NotFoundFileTypeException;
use LTDBeget\ApacheConfigurator\Interfaces\iClass;
use LTDBeget\ApacheConfigurator\Interfaces\iConfigurationFile;
use LTDBeget\ApacheConfigurator\Interfaces\iDirective;
use LTDBeget\ApacheConfigurator\Interfaces\iDirectivePath;
use LTDBeget\ApacheConfigurator\Interfaces\iInnerDirectiveAble;
use LTDBeget\ApacheConfigurator\Interfaces\iType;
use LTDBeget\ApacheConfigurator\Directives\Available;

class ConfigurationFile implements iConfigurationFile, iInnerDirectiveAble, iType, iClass
{
    const SERVER_CONFIG = 'serverConfig';
    const HTACCESS      = "htaccess";

    /**
     * type of current configurationFile "serverConfig", "htaccess"
     * @var String
     */
    protected $fileType;

    protected $innerDirectives = [];


    function __construct($fileType)
    {
        if(!in_array($fileType, [ConfigurationFile::SERVER_CONFIG, ConfigurationFile::HTACCESS])) {
            throw new NotFoundFileTypeException("$fileType is not allowed file type for apache config file");
        }
        $this->fileType = $fileType;
    }

    /**
     * @param iDirectivePath $directivePath
     * @return Directive
     */
    public function addDirective(iDirectivePath $directivePath)
    {
        if($directivePath->isRoot()) {
            $context = $this;
        } else {
            $context = $this->findByPath($directivePath->getParentPath());
        }

        if(is_null($context)) {
            $context = $this->addDirective($directivePath->getParentPath());
        }

        $className = "Available\\".$directivePath->getDirectiveType();
        if(class_exists($className)) {
            $directive = new $className($directivePath->getDirectiveValue(), $context);
        } else {
            $directive = new Unknown($directivePath->getDirectiveType(), $directivePath->getDirectiveValue(), false, $context);
        }
        $context->appendInnedDirective($directive);
        return $directive;
    }

    /**
     * @param iDirectivePath $directivePath
     * @param String $value
     * @throws Exceptions\NotAllowedValueException
     * @throws NotFoundDirectiveException
     */
    public function changeDirective(iDirectivePath $directivePath, $value)
    {
        $this->findByPath($directivePath, true)->setValue($value);
    }

    /**
     * @param iDirectivePath $directivePath
     * @throws NotAllowedContextException
     * @throws NotFoundDirectiveException
     */
    public function removeDirective(iDirectivePath $directivePath)
    {
        $directive = $this->findByPath($directivePath, true);
        /**
         * @var Directive $context
         */
        $context = $directive->getContext();
        $context->detachInnerDirective($directive);
    }

    /**
     * @return string type of current configurationFile "serverConfig", "htaccess"
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * return all innerDirectives
     * @return iDirective[]
     */
    public function getInnerDirectives()
    {
        return $this->innerDirectives;
    }

    /**
     * iterate throw all children of iInnerDirectiveAble
     * @yield iContextAble
     */
    public function iterateChildren()
    {
        foreach($this->getInnerDirectives() as $directive) {
            /**
             * @var Directive $directive
             */
            yield $directive;
            $directive->iterateChildren();
        }
    }

    /**
     * @internal
     * add InnerDirective in iInnerDirectiveAble
     * @param iDirective $directive
     * @return mixed
     */
    public function appendInnedDirective(iDirective $directive)
    {
        array_push($this->innerDirectives, $directive);
    }

    /**
     * detach innerDirective from iInnerDirectiveAble
     * @param iDirective $directive
     * @throws NotAllowedContextException
     */
    public function detachInnerDirective(iDirective $directive)
    {
        /**
         * @var Directive $directive
         */
        if($this !== $directive->getContext()) {
            throw new NotAllowedContextException("Trying to detach from {$this->getType()} directive {$directive->getContext()} which is not its context");
        }

        foreach($this->innerDirectives as $key => $innerDirective) {
            if($directive === $innerDirective) {
                unset($this->innerDirectives[$key]);
            }
        }
    }

    /**
     * Name of Apache directive or file type
     * @return String
     */
    public function getType()
    {
        return $this->fileType;
    }

    /**
     * className
     * @return String
     */
    public static function className()
    {
        return __CLASS__;
    }


    /**
     * @param iDirectivePath $directivePath
     * @param bool $throwException
     * @return Directive|null
     * @throws NotFoundDirectiveException
     */
    protected function findByPath(iDirectivePath $directivePath, $throwException = false)
    {
        foreach($this->iterateChildren() as $directive) {
            /**
             * @var Directive $directive
             */
            if($directivePath->comparePath($directive->getPath())) {
                return $directive;
            }
        }
        if($throwException) {
            $path = json_encode($directivePath->getPath());
            throw new NotFoundDirectiveException("Directive by path does not exist: $path");
        }
        return null;
    }
}
