<?php namespace System\Service\Form;

use Hdphp\Kernel\Facade;

class FormFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'Form';
    }
}