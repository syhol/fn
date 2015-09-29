<?php

if ( ! function_exists('fnf'))
{
    function fnf()
    {
        return Syhol\Fn\FnContainer::getGlobal()
            ->get('fnFactory')
            ->apply(func_get_args());
    }
}

if ( ! function_exists('fnc'))
{
    function fnc()
    {
        return Syhol\Fn\FnContainer::getGlobal()
            ->get('fnContainer')
            ->apply(func_get_args());
    }
}
