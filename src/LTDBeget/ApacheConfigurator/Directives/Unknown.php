<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 17:18
 */


namespace LTDBeget\ApacheConfigurator\Directives;


use LTDBeget\ApacheConfigurator\Exceptions\NotAllowedContextException;
use LTDBeget\ApacheConfigurator\Interfaces\iDirective;
use LTDBeget\ApacheConfigurator\Interfaces\iInnerDirectiveAble;

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

    /**
     * @param String $type
     * @param String $value
     * @param Boolean $isSection
     * @param iInnerDirectiveAble $context
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

    public function getType()
    {
        return $this->type;
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