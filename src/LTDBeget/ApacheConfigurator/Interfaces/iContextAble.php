<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 19:28
 */


namespace LTDBeget\ApacheConfigurator\Interfaces;


interface iContextAble {
    /**
     * Current context of directive or root of file
     * @return iInnerDirectiveAble
     */
    public function getContext();

    /**
     * Iterate from iContextAble through it parents to the root
     * @yield iInnerDirectiveAble
     */
    public function iterateParent();

    /**
     * Return object of absolute path to this iContextAble directive
     * @return iDirectivePath
     */
    public function getPath();
}