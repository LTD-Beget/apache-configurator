<?php
/**
 * @author: Viskov Sergey
 * @date: 28.04.15
 * @time: 17:13
 */


namespace LTDBeget\ApacheConfigurator\Directives;


use LTDBeget\ApacheConfigurator\Exceptions\WrongDirectivePathFormat;
use LTDBeget\ApacheConfigurator\Interfaces\iDirectivePath;

class DirectivePath implements iDirectivePath
{
    /**
     * path with format ["directive" => $directiveName, "value" => $value, innerDirective => []]
     * @var array
     */
    protected $path;

    /**
     *
     * @param $path
     * @throws WrongDirectivePathFormat
     */
    function __construct($path)
    {
        if($this->isJson($path)) {
            $path = json_decode($path, true);
        }
        $this->checkFormat($path);
        $this->path = $path;
    }

    /**
     * @return Array exploded valid path with format ["directive" => $directiveName, "value" => $value, innerDirective => []]
     * where is a name of Apache directive
     * and value its value
     * and innerDirective if directive is a section, here its inner directive with same format
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * get Directive type (Apache directive name) of last directive in path
     * @return String
     */
    public function getDirectiveType()
    {
        $path = $this->getPath();
        $directive = $path["directive"];
        while(isset($path["innerDirective"]) and is_array($path["innerDirective"]) and count($path["innerDirective"])) {
            $directive = $path["directive"];
            $path = $path["innerDirective"];
        }
        return $directive;
    }

    /**
     * get Directive value (Apache directive value) of last directive in path
     * @return String
     */
    public function getDirectiveValue()
    {
        $path = $this->getPath();
        $value = $path["value"];
        while(isset($path["innerDirective"]) and is_array($path["innerDirective"]) and count($path["innerDirective"])) {
            $value = $path["value"];
            $path = $path["innerDirective"];
        }
        return $value;
    }

    /**
     * get Path object of parent for this directive
     * @return iDirectivePath
     */
    public function getParentPath()
    {
        $path = $this->getPath();
        $parentPath = [];
        while(isset($path["innerDirective"]) and is_array($path["innerDirective"]) and count($path["innerDirective"])) {
            $parentPath = $path;
            unset($parentPath["innerDirective"]);
            $path = $path["innerDirective"];
        }
        return new DirectivePath($parentPath);
    }

    /**
     * Is this path for root of Apache configuration file
     * @return bool
     */
    public function isRoot()
    {
        return $this->path == [];
    }

    /**
     * Compare this iDirectivePath with another
     * @param iDirectivePath $directivePath
     * @return boolean true if paths identical
     */
    public function comparePath(iDirectivePath $directivePath)
    {
        return $this->isEqualPath($this->getPath(), $directivePath->getPath());
    }

    /**
     * Is two path equal in iDirectivePath format
     * @param $standardPath
     * @param $comparablePath
     * @return bool
     */
    protected function isEqualPath($standardPath, $comparablePath)
    {
        if($standardPath == [] and $comparablePath == []) {
            return true;
        }

        if($standardPath["directive"] !== $comparablePath["directive"]) {
            return false;
        }

        if($standardPath["value"] !== $comparablePath["value"]) {
            return false;
        }

        if(isset($standardPath["innerDirective"])) {
            if(isset($comparablePath["innerDirective"])) {
                return $this->isEqualPath($standardPath["innerDirective"], $comparablePath["innerDirective"]);
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Checks format of the object path.
     * Right format is ["directive" => $directiveName, "value" => $value, innerDirective => []]
     * where directive is a name of Apache directive
     * and {value} its value
     * @param array $directive
     * @throws WrongDirectivePathFormat
     */
    protected function checkFormat(array $directive)
    {
        if($directive != []) {
            if(!isset($directive["directive"])) {
                throw new WrongDirectivePathFormat("For all directives in DirectivePath need to define directive value");
            }

            if(!isset($directive["value"])) {
                throw new WrongDirectivePathFormat("For all directives in DirectivePath need to define directive value");
            }

            if(isset($directive["innerDirective"]) and !is_array($directive["innerDirective"])) {
                throw new WrongDirectivePathFormat("If isset inner directives, it need to be array");
            } else {
                $this->checkFormat($directive["innerDirective"]);
            }
        }
    }

    /**
     * is Json string
     * @param String $string
     * @return bool
     */
    protected function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}