<?php

namespace Alisuliman\CodeGenerator\Helpers;

class Str
{
    protected $string;

    public function __construct($string)
    {
        $this->string = $string;
    }
    
    public function replace($search, $replace)
    {
        $this->string = str_replace($search, $replace, $this->string);
        return $this;
    }

    public function getString()
    {
        return $this->string;
    }
}
