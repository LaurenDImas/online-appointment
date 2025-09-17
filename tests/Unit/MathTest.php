<?php

use App\Helpers\Calculator;

test('add function', function () {
    $calculator = new Calculator();
    expect($calculator->add([1,5,7]))->toEqual(13);
});

test('divide function', function () {
    $calculator = new Calculator();
    expect($calculator->divide(100,5))->toEqual(20);
});
