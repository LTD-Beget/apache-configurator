<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 15:19
 */


namespace LTDBeget\apacheConfigurator;


use LTDBeget\apacheConfigurator\directives\DirectivePath;
use LTDBeget\apacheConfigurator\directives\Unknown;
use LTDBeget\apacheConfigurator\exceptions\NotAllowedContextException;
use LTDBeget\apacheConfigurator\exceptions\NotFoundDirectiveException;
use LTDBeget\apacheConfigurator\exceptions\NotFoundFileTypeException;
use LTDBeget\apacheConfigurator\exceptions\WrongDirectivePathFormat;
use LTDBeget\apacheConfigurator\interfaces\iConfigurationFile;
use LTDBeget\apacheConfigurator\interfaces\iDirective;
use LTDBeget\apacheConfigurator\interfaces\iDirectivePath;

class ConfigurationFile implements iConfigurationFile
{
    const SERVER_CONFIG = 'serverConfig';
    const HTACCESS      = "htaccess";

    /**
     * type of current configurationFile "serverConfig", "htaccess"
     * @var String
     */
    protected $fileType;

    protected $innerDirectives = [];


    public function __construct($fileType)
    {
        if(!in_array($fileType, [ConfigurationFile::SERVER_CONFIG, ConfigurationFile::HTACCESS])) {
            throw new NotFoundFileTypeException("$fileType is not allowed file type for apache config file");
        }

        $this->fileType = $fileType;
    }

    /**
     * @param String $directiveName name of Apache directive
     * @param String $value value of Apache directive
     * @param iDirectivePath $contextPath path to context
     * @throws NotFoundDirectiveException
     * @throws WrongDirectivePathFormat
     * @return iDirective
     */
    public function addDirective($directiveName, $value, iDirectivePath $contextPath = null)
    {
        $context = $this->getContextByPath($contextPath);

        $directivePath = $context->getPath()->makeChildDirectivePath($directiveName, $value);

        if($this->findByPath($directivePath)) {
            throw new WrongDirectivePathFormat("Directive already exists by path: ".json_encode($directivePath->getPath()));
        }

        $className = "LTDBeget\\apacheConfigurator\\directives\\available\\".$directivePath->getDirectiveType();
        if(class_exists($className)) {
            $directive = new $className($directivePath->getDirectiveValue(), $context);
        } else {
            $directive = new Unknown($directivePath->getDirectiveType(), $directivePath->getDirectiveValue(), false, $context);
        }

        $context->appendInnedDirective($directive);

        return $directive;
    }

    /**
     * @param iDirective $directive
     * @return void
     */
    public function removeDirective(iDirective $directive)
    {
        $context = $directive->getContext();
        $context->detachInnerDirective($directive);
    }

    /**
     * string type of current configurationFile "serverConfig", "htaccess"
     * @return String
     */
    public function getType()
    {
        return $this->fileType;
    }

    /**
     * Full name of ConfigurationFile
     * @return String
     */
    public static function getFullName()
    {
        return __CLASS__;
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
     * @yield iDirective
     * @return iDirective[]
     */
    public function iterateChildren()
    {
        foreach($this->getInnerDirectives() as $directive) {
            foreach($directive->iterateChildren() as $innerDirective) {
                yield $innerDirective;
            }
        }
    }

    /**
     * @internal
     * add InnerDirective in iInnerDirectiveAble
     * @param iDirective $directive
     * @return mixed
     * @throws NotAllowedContextException
     */
    public function appendInnedDirective(iDirective $directive)
    {
        if($this !== $directive->getContext()) {
            throw new NotAllowedContextException("Trying to append to {$this->getType()} directive {$directive->getContext()} which is not its context");
        }

        $this->innerDirectives[] = $directive;
    }

    /**
     * detach innerDirective from iInnerDirectiveAble
     * @param iDirective $directive
     * @throws NotAllowedContextException
     */
    public function detachInnerDirective(iDirective $directive)
    {
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
     * @param iDirectivePath $directivePath
     * @param bool $throwException
     * @return iDirective|null
     * @throws NotFoundDirectiveException
     */
    public function findByPath(iDirectivePath $directivePath, $throwException = false)
    {
        foreach ($this->iterateChildren() as $directive) {
            if ($directivePath->comparePath($directive->getPath())) {
                return $directive;
            }
        }

        if ($throwException) {
            $path = json_encode($directivePath->getPath());
            throw new NotFoundDirectiveException("Directive by path does not exist: $path");
        }
        return null;
    }

    /**
     * Return path of root file
     * @return iDirectivePath
     */
    public function getPath()
    {
        return new DirectivePath([]);
    }

    /**
     * @param iDirectivePath|null $contextPath
     * @return ConfigurationFile|iDirective
     * @throws NotFoundDirectiveException
     * @throws WrongDirectivePathFormat
     */
    protected function getContextByPath(iDirectivePath $contextPath)
    {
        if(is_null($contextPath) or $contextPath->isRoot()) {
            $context = $this;
        } else {
            $context = $this->findByPath($contextPath);
            if(is_null($context)) {
                $context = $this->addDirective($contextPath->getDirectiveType(), $contextPath->getDirectiveValue(), $contextPath->getParentPath());
            }
        }

        return $context;
    }
}
