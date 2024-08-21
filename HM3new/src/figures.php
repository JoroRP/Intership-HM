<?php

namespace GeorgiRadoslavov\Hm3new;

/*
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

$required_options = ['type', 'calculation', 'shape', 'radius'];

foreach ($required_options as $option) {
    if (!isset($options[$option])) {
        echo 'Error: Missing required option --' . $option . "\n";
        exit(1);
    }
}

$radius = (float)$options["radius"];
$shape = $options["shape"];
$calculation = $options["calculation"];

$a = 0;
$b = 0;
$c = 0;

if($shape == "triangle")
{
    $a = $options["a"];
    $b = $options["b"];
    $c = $options["c"];
}

$result = calculateFigure($calculation, $shape, $radius, $a, $b, $c);

echo 'Your result is ' . $result . "\n";

*/

function calculateFigure(string $calculation, string $shape, float $radius, float $a = null, float $b = null, float $c = null): float|string {
    $result = 0;

    if(!($calculation == "area" || $calculation == "perimeter"))
        return "Error: Invalid calculation type provided.";

    switch ($shape) {
        case "triangle":
            if ($a !== null && $b !== null && $c !== null) {
                if ($calculation === "area") {
                    $result = 0.5 * $c * $radius;
                } elseif ($calculation === "perimeter") {
                    $result = $a + $b + $c;
                }
            } else {
                return "Error: Missing values for sides a, b, c";
            }
            break;

        case "square":
            if ($calculation === "area") {
                $result = $radius * $radius;
            } elseif ($calculation === "perimeter") {
                $result = 4 * $radius;
            }
            break;

        case "circle":
            if ($calculation === "area") {
                $result = M_PI * $radius * $radius;
            } elseif ($calculation === "perimeter") {
                $result = 2 * M_PI * $radius;
            }
            break;

        default:
            return "Error: Invalid figure provided.";
    }

    return round($result, 2);
}