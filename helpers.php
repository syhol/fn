<?php

if ( ! function_exists('fn'))
{
    function fn(callable $fn)
    {
        return (new Syhol\Fn\FnFactory)->parse($fn);
    }
}