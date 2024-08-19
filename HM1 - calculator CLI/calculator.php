<?php

$longopts  = array(
    "type:",    
    "operator:",   
    "param1:",       
    "param2:",          
);

$options = getopt("", $longopts);
//var_dump($options);
//hello world
//feature 1 comment

$required_options = ['type', 'operator', 'param1', 'param2'];

foreach ($required_options as $option) {
    if (!isset($options[$option])) {
        echo 'Error: Missing required option --' . $option . "\n";
        exit(1);
    }
}

$a = $options["param1"];
$b = $options["param2"];

$result;

switch($options["operator"]){
    case "plus":
        $result = $a + $b;
        break;

    case "minus":
        $result = $a - $b;
        break;

    case "multiply":
        $result = $a * $b;
        break;

    case "divide":
        $result = $a / $b;
        break;

    case "modulo":
        $result = $a % $b;
        break;

    case "exponentiation":
        $result = $a ** $b;
        break;
        
}

echo 'Your result is ' . $result;
    