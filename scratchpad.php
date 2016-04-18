<?php

// Named Function
// Anon Function
// Object __invoke

// Object method
// Object static method 
// Object __call
// Object __callStatic

// simple composition

$not = fn(function($value) { return ! $value; });
$even = fn(function($value) { return $value % 2 === 0; });
$empty = fn(function($value) { return empty($value); });
$min = fn('min');
$notEmpty = $empty->pipe($not);
$odd = $even->pipe($not);

$isFalse = $even->pipe(fn('not'));

array_filter([false, true, false, true], $isFalse);


// Named properties

$add = fn(function($a, $b) {
    return $a + $b;
});

//$add['a'] = 5; // 5 bound to $a
//
//$add(3); //8
//
//$add['b'] = 5; // 5 bound to $b
//
//$add(); // 10

$add = $add->withoutPartials();

$add['b'] = 5;  // 5 bound to a $b

$add7and5 = $add->partialFor('b', 7); // 7 bound to a $b in a returned clone

$add7and5(); // 10

// Index properties

$add9 = $add->partialAt(0, 9); // 9 bound to a the param at index 1 (on a 0 based index)

$add9and7 = $add9->partialAt(1, 7); // 9 bound to a the param at index 2 (on a 0 based index)

$add9and7(); // 16

$add9(2); // 11

$add[] = 1; // 1 bound to $a

$add(2); // 3

$add[] = 6; // 6 bound to $b

$add(); // 7

$add[1] = 5; // 5 bound to $b

$add(); // 6

// Complicated composition

$addDoubles = fn(function($a, $b, $func) {
    list($a, $b) = [$b * 2, $a * 2];
    return $func($a, $b);
})->partialRight($add);

$add = $add->pipe(function($response) {
    return 'The results is: ' . $response;
});
//
//$add = $add->onFail(function(Exception $e) {
//    Monolog::error($e->getMessage());
//});

$setMember = function($memberId, $companyId) {
    // Do something
};

$applyMember = function($response) use($setMember) {
    $setMember($response[0], $response[3]);
    return $response;
};

$add->pipe($applyMember);


// Methods also
// @deprecated
// fn([$instance, 'methodName'])
//     ->pipe([$a1, 'method1'])
//     ->pipe([$a2, 'method2'])
//     ->pipe([$a3, 'method3'])
//     ->pipe([$a4, 'method4']);


// Anons get there params bound
//
//$func = fn(function($var1) {
//    return $var1 + $this->var2;
//});
//
//$func['var2'] = 7;
//
//$func(3); // 10
//
//$func2 = f(function() {
//    return $this->greet + ' ' + $this->name;
//});
//
//$func2['name'] = 'Simon';
//$func2['greet'] = 'Hello';
//
//$func2();
//
//$getFromArray = fn(function($var) {
//    return $var[$this->key];
//});
//
//$getFromArray['key'] = 'bar';
//
//$getFromArray(['foo' => 1, 'bar' => 2]); // 2


// Polymorphic functions

//$poly = fn()->poly(
//    function($a) {
//        return '$a is great but only a?';
//    },
//    function($a, $b) {
//        return 'Perfect, thats $a $b';
//    },
//    function($a, $b, $c) {
//        return '$a, $b and $c! wow, thats too much?';
//    }
//);
//
//$poly(1); // $a is great but only a?
//$poly(1, 2); // Perfect, thats $a $b
//$poly(1, 2 ,3); // $a, $b and $c! wow, thats too much?


// $f->parse(function(){
//     
// }, function(){
//     
// });

// $f(function(){
//     
// })->parse(function(){
//     
// });


// $func = $f(function(){
//     
// });

// $func->parse(function(){
//     
// });

// Invoking the facade and others

//Fn::parse(function(){});
fn(function(){});
FnFactory::parse(function(){});
