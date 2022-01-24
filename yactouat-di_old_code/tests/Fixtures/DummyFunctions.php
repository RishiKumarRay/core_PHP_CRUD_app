<?php

$dummyFunction = fn(): string => "dummy function";

function dummyFunction2(): string {
    return "dummy function 2";
}

$dummyFunction3 = fn(string $val1 = "first val", string $val2 = "second val"): string => $val1 . " " . $val2;

$dummyFunction4 = fn(string $val1, string $val2): string => $val1 . " " . $val2;