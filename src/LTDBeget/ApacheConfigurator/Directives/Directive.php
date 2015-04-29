<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 17:09
 */


namespace LTDBeget\ApacheConfigurator\Directives;


use LTDBeget\ApacheConfigurator\Exceptions\NotAllowedContextException;
use LTDBeget\ApacheConfigurator\Exceptions\NotAllowedValueException;
use LTDBeget\ApacheConfigurator\Interfaces\iClass;
use LTDBeget\ApacheConfigurator\Interfaces\iContextAble;
use LTDBeget\ApacheConfigurator\Interfaces\iDirective;
use LTDBeget\ApacheConfigurator\Interfaces\iDirectivePath;
use LTDBeget\ApacheConfigurator\Interfaces\iInnerDirectiveAble;
use LTDBeget\ApacheConfigurator\Interfaces\iType;

class Directive implements iDirective, iContextAble, iInnerDirectiveAble, iType, iClass
{
    /**
     * Site of Apache full documentation
     * @var string
     */
    protected $apacheSite = "http://httpd.apache.org";

    /**
     * @var String
     */
    protected $module;

    /**
     * @var Array|null
     */
    protected $allowedContext;

    /**
     * @var iInnerDirectiveAble
     */
    protected $context;

    /**
     * @var Array|null
     */
    protected $allowedValues = null;

    /**
     * @var String
     */
    protected $value;

    /**
     * Text description of apache directive
     * @var String
     */
    protected $description;

    /**
     * link to full description of apache directive
     * @var String
     */
    protected $apacheDocLink = "/docs/2.4/mod/directives.html";

    /**
     * Example Apache directive syntax
     * @var String
     */
    protected $syntax;

    /**
     * @var Boolean
     */
    protected $isSection = false;

    /**
     * @var iContextAble[]
     */
    protected $innerDirectives = null;


    /**
     * @param String $value
     * @param iInnerDirectiveAble $context
     */
    public function __construct($value, $context)
    {
        $this->setValue($value);
        $this->setContext($context);
    }

    /**
     * Late static bindings className
     * @return String
     */
    public static function className()
    {
        return __CLASS__;
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Name of Apache directive
     * @return String
     */
    public function getType()
    {
        $classNameWithNamespace = get_class($this);
        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\')+1);
    }

    /**
     * Value of Apache directive
     * @return String
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param String $value
     * @return void
     * @throws NotAllowedValueException
     */
    public function setValue($value)
    {
        if($this->isAllowedValue($value)) {
            $this->value = $value;
        } else {
            throw new NotAllowedValueException("Its now allowed to set {$value} in {$this->getType()} directive. Example syntax is {$this->getSyntax()}");
        }
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax()
    {
        return $this->syntax;
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Return link to full description of apache directive
     * @return String
     */
    public function getApacheDocLink()
    {
        return $this->apacheSite.$this->apacheDocLink;
    }

    /**
     * Current context of directive or root of file
     * @return iContextAble
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param iInnerDirectiveAble $context
     * @throws NotAllowedContextException
     */
    public function setContext(iInnerDirectiveAble $context)
    {
        if(($context instanceof iInnerDirectiveAble) and ($context instanceof iType)) {
            if($this->isAllowedContext($context)) {
                $this->context = $context;
            } else {
                throw new NotAllowedContextException("Its now allowed to set {$this->getType()} in {$context->getType()}");
            }
        } else {
            throw new NotAllowedContextException("Wrong interface for context of {$this->getType()}");
        }
    }

    /**
     * return all innerDirectives
     * @return iDirective[]
     */
    public function getInnerDirectives()
    {
        return $this->isSection()?$this->innerDirectives:null;
    }

    /**
     * is this directive can include inner directives
     * @return boolean
     */
    public function isSection()
    {
        return $this->isSection;
    }

    /**
     * add InnerDirective in iInnerDirectiveAble
     * @param iDirective $directive
     * @throws NotAllowedContextException
     */
    public function appendInnedDirective(iDirective $directive)
    {
        if(!$this->isSection()) {
            throw new NotAllowedContextException("You trying add directive as inner in non section directive");
        }
        if(!($directive instanceof iContextAble)) {
            throw new NotAllowedContextException("Inner directive  must be iContextAble");
        }

        if($directive->getContext() !== $this->getContext()) {
            throw new NotAllowedContextException("trying add inner directive in {$this->getType()} with context of another directive");
        }
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
     * Iterate from iContextAble through it parents to the root
     * @yield iInnerDirectiveAble
     */
    public function iterateParent()
    {
        $context = $this->getContext();
        if($context instanceof iContextAble) {
            yield $context;
            $context->iterateParent();
        }
    }

    /**
     * iterate throw all children of iInnerDirectiveAble
     * @yield iContextAble
     */
    public function iterateChildren()
    {
        if($this->isSection()) {
            foreach($this->getInnerDirectives() as $directive) {
                /**
                 * @var Directive $directive
                 */
                yield $directive;
                $directive->iterateChildren();
            }
        }
    }

    /**
     * Return object of absolute path to this iContextAble directive
     * @return iDirectivePath
     *
     * TODO Its bad, that Directive knows about inner format of DirectivePath
     */
    public function getPath()
    {

        $path = [
            "directive" => $this->getType(),
            "value"     => $this->getValue()
        ];
        $finalPath = null;
        foreach($this->iterateParent() as $directive) {
            if($directive instanceof Directive) {
                $finalPath = [
                    "directive"      => $directive->getType(),
                    "value"          => $directive->getValue(),
                    "innerDirective" => $path,
                ];
                $path = $finalPath;
            }
        }
        $path = is_null($finalPath)?$finalPath:$path;
        return new DirectivePath($path);
    }

    /**
     * Check current directive context on allowed (in what context this directive can be placed)
     * @param Directive $context
     * @return bool
     */
    protected function isAllowedContext(Directive $context)
    {
        if($context->className() == Unknown::className()) {
            return true;
        }

        return is_null($this->allowedContext)?true:in_array($context->getType(), $this->allowedContext);
    }

    /**
     * Set allowed context of concrete directive
     * override in inheritor if need
     */
    protected function setAllowedContext()
    {
        $this->allowedContext = null;
    }

    /**
     * Check current value of directive, on its syntax, if syntax rules defined
     * @param $value
     * @return bool
     */
    protected function isAllowedValue($value)
    {
        if(is_null($this->allowedValues)) {
            return true;
        } else {
            return in_array($value, $this->allowedValues);
        }

    }
}