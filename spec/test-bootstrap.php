<?php
namespace spec\Syhol\Fn;

class TestClass
{
    public function test($input)
    {
        return 'whooo ' . $input;
    }

    public static function staticTest($input)
    {
        return 'whooo ' . $input;
    }
}

class MagicClass
{
    private function _test($input)
    {
        return 'whooo ' . $input;
    }

    private static function _staticTest($input)
    {
        return 'foooo ' . $input;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this, '_' . $method], $args);
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array([static::class, '_' . $method], $args);
    }
}

class InvokeClass
{
    public function __invoke()
    {
        var_dump(func_get_args());
    }
}

function test_user_defined_fn($input)
{
    return 'whooo ' . $input;
}
