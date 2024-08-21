<?php

namespace GeorgiRadoslavov\Hm3new;
/*
$longopts  = array(
    "type:",
    "operator:",
    "param1:",
    "param2:",
);

$options = getopt("", $longopts);

$required_options = ['type', 'operator', 'param1', 'param2'];

foreach ($required_options as $option) {
    if (!isset($options[$option])) {
        echo 'Error: Missing required option --' . $option . "\n";
        exit(1);
    }
}

$a = $options["param1"];
$b = $options["param2"];

$result = calculate($options["operator"], $a, $b);

echo 'Your result is ' . $result . "\n";

*/
function calculate($operator, $a, $b) {
    switch ($operator) {
        case "plus":
            return $a + $b;
        case "minus":
            return $a - $b;
        case "multiply":
            return $a * $b;
        case "divide":
            if ($b == 0) {
                return "Error: Division by zero is not allowed.";
            }
            return $a / $b;
        case "modulo":
            if ($b == 0) {
                return "Error: Modulo by zero is not allowed.";
            }
            return $a % $b;
        case "exponentiation":
            return $a ** $b;
        default:
            return "Error: Invalid operator provided.";
    }
}
