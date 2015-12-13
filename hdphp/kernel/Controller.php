<?php namespace hdphp\kernel;

class Controller
{
    public function __construct ()
    {
        if (method_exists ($this, '__init'))
        {
            $this->__init ();
        }
    }
}