<?php
$ffi = FFI::cdef(
    "int fibonacci(int n);",
    "testdll.dll");

function fibonacci(int $n)
{
    if ($n == 1) return $n;
    if ($n == 2) return 1;
    return fibonacci($n - 1) + fibonacci($n - 2);
}


$startTime = microtime(true);
var_dump($ffi->fibonacci(40));
printf("ffi所用时间：%f\n", microtime(true) - $startTime);


$startTime = microtime(true);
var_dump(fibonacci(40));
printf("原生所用时间：%f\n", microtime(true) - $startTime);
