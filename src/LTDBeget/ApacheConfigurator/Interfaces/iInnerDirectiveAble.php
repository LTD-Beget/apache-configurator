<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 19:33
 */


namespace LTDBeget\ApacheConfigurator\Interfaces;


interface iInnerDirectiveAble
{
    /**
     * return all innerDirectives
     * @return iContextAble[]
     */
    public function getInnerDirectives();

    /**
     * iterate throw all children of iInnerDirectiveAble
     * @yield iContextAble
     */
    public function iterateChildren();

    /**
     * add InnerDirective in iInnerDirectiveAble
     * @param iDirective $directive
     */
    public function appendInnedDirective(iDirective $directive);

    /**
     * detach innerDirective from iInnerDirectiveAble
     * @param iDirective $directive
     */
    public function detachInnerDirective(iDirective $directive);
}