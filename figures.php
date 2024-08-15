<?php

$longopts  = array(
    "type:",    
    "calculation:",   
    "shape:",       
    "radius:",
    "a::",
    "b::",
    "c::",          
);

$options = getopt("", $longopts);
//var_dump($options);

$required_options = ['type', 'calculation', 'shape', 'radius'];

foreach ($required_options as $option) {
    if (!isset($options[$option])) {
        echo 'Error: Missing required option --' . $option . "\n";
        exit(1);
    }
}

$radius = $options["radius"];


$result;

switch($options["shape"]){

    case "triangle":
        $a = $options["a"];
        $b = $options["b"];
        $c = $options["c"];

        if ($a && $b && $c) {
            if ($options["calculation"]=="area") {
                $result= 0.5 * $c * $radius;
                $result = round($result,2);
            }
            else if($options["calculation"]=="perimeter"){
                $result = $a + $b + $c;
                $result = round($result,2);
            }
        } else {
            echo "Error: Missing values for sides a,b,c";
            exit(1);
        }

        break;
    case "square":
        if ($options["calculation"]=="area") {
            $result= $radius * $radius;
            $result = round($result,2);
        }
        else if($options["calculation"]=="perimeter"){
            $result= 4 * $radius;
            $result = round($result,2);
        }

        break;
     case "circle":
        if ($options["calculation"]=="area") {
            $result= M_PI * $radius * $radius;
            $result = round($result,2);
        }
        else if($options["calculation"]=="perimeter"){
            $result= 2 * M_PI * $radius;
            $result = round($result,2);
        }
        
        break;
   
}

echo 'Your result is ' . $result;
    