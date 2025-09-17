<?php

namespace App\Helpers;

class Calculator
{
    public function add(array $array){
        $result = 0;
        foreach ($array as $value) {
            $result += $value;
        }
        return $result;
    }

    public function divide($a, $b): float|int
    {
        return $a/$b;
    }
}
