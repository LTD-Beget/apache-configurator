<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 17:18
 */


namespace LTDBeget\ApacheConfigurator\Directives;


use LTDBeget\ApacheConfigurator\Exceptions\NotAllowedContextException;
use LTDBeget\ApacheConfigurator\Interfaces\iDirective;
use LTDBeget\ApacheConfigurator\Interfaces\iContext;

class Unknown extends Directive
{
    /**
     * @var String
     */
    protected $module = "unknown";

    /**
     * Text description of apache directive
     * @var String
     */
    protected $description = "unknown";

    /**
     * Example Apache directive syntax
     * @var String
     */
    protected $syntax = 'unknown';

    /**
     * Name of unknown directive as it is
     * @var String
     */
    protected $type;

    protected $isSection = false;

    /**
     * @param String $type
     * @param String $value
     * @param Boolean $isSection
     * @param iContext $context
     * @throws \LTDBeget\ApacheConfigurator\Exceptions\NotAllowedContextException
     * @throws \LTDBeget\ApacheConfigurator\Exceptions\NotAllowedValueException
     */
    public function __construct($type, $value, $isSection, $context)
    {
        $this->type = $type;
        $this->setValue($value);
        $this->isSection = $isSection;
        if($this->isSection()) {
            $this->innerDirectives = [];
        }
        $this->setContext($context);
    }

    /**
     * Name of Apache directive
     * @return String
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * the source module which defines the directive
     * @return String
     */
    public function getModule()
    {
        return 'unknown';
    }

    /**
     * Return text description of apache directive
     * @return String
     */
    public function getDescription()
    {
        return 'unknown';
    }

    /**
     * Return Apache directive Syntax
     * @return String
     */
    public function getSyntax()
    {
        return 'unknown';
    }

    /**
     * is this directive can include inner directives
     * @return boolean
     */
    public function isSection()
    {
        return $this->isSection();
    }

    /**
     * add InnerDirective in iInnerDirectiveAble
     * @param iDirective $directive
     * @throws NotAllowedContextException
     */
    public function appendInnedDirective(iDirective $directive)
    {
        if(!$this->isSection()) {
            $this->isSection = true;
            $this->innerDirectives = [];
        }
        parent::appendInnedDirective($directive);
    }

}