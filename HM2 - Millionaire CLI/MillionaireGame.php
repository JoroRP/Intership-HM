<?php

$questionnaire = [
    [
        "question" => "What is the tallest mountain in the world?",
        "options" => [
            "0" => "K2",
            "1" => "Mount Everest",
            "2" => "Kangchenjunga",
            "3" => "Lhotse"
        ],
        "correct" => "Mount Everest"
    ],
    [
        "question" => "Which of the following is a reptile?",
        "options" => [
            "0" => "Frog",
            "1" => "Turtle",
            "2" => "Rabbit",
            "3" => "Hawk"
        ],
        "correct" => "Turtle"
    ],
    [
        "question" => "What is the primary ingredient in guacamole?",
        "options" => [
            "0" => "Tomato",
            "1" => "Onion",
            "2" => "Avocado",
            "3" => "Lettuce"
        ],
        "correct" => "Avocado"
    ],
    [
        "question" => "Which country is known for the Eiffel Tower?",
        "options" => [
            "0" => "Germany",
            "1" => "Italy",
            "2" => "Spain",
            "3" => "France"
        ],
        "correct" => "France"
    ],
    [
        "question" => "What do bees produce?",
        "options" => [
            "0" => "Milk",
            "1" => "Honey",
            "2" => "Wool",
            "3" => "Silk"
        ],
        "correct" => "Honey"
    ],
    [
        "question" => "Which organ is primarily responsible for pumping blood?",
        "options" => [
            "0" => "Liver",
            "1" => "Brain",
            "2" => "Heart",
            "3" => "Kidney"
        ],
        "correct" => "Heart"
    ],
    [
        "question" => "What is the chemical symbol for gold?",
        "options" => [
            "0" => "Ag",
            "1" => "Au",
            "2" => "Pb",
            "3" => "Fe"
        ],
        "correct" => "Au"
    ],
    [
        "question" => "Which of these is a web browser?",
        "options" => [
            "0" => "Google",
            "1" => "Facebook",
            "2" => "Twitter",
            "3" => "Chrome"
        ],
        "correct" => "Chrome"
    ],
    [
        "question" => "Which planet is closest to the Sun?",
        "options" => [
            "0" => "Earth",
            "1" => "Venus",
            "2" => "Mercury",
            "3" => "Mars"
        ],
        "correct" => "Mercury"
    ],
    [
        "question" => "What is the name of the fairy in Peter Pan?",
        "options" => [
            "0" => "Cinderella",
            "1" => "Tinker Bell",
            "2" => "Ariel",
            "3" => "Belle"
        ],
        "correct" => "Tinker Bell"
    ],
    [
        "question" => "Which shape has four equal sides?",
        "options" => [
            "0" => "Rectangle",
            "1" => "Triangle",
            "2" => "Square",
            "3" => "Circle"
        ],
        "correct" => "Square"
    ],
    [
        "question" => "Which element does O represent on the periodic table?",
        "options" => [
            "0" => "Osmium",
            "1" => "Oxygen",
            "2" => "Opium",
            "3" => "Ozone"
        ],
        "correct" => "Oxygen"
    ],
    [
        "question" => "Which is the largest desert in the world?",
        "options" => [
            "0" => "Sahara",
            "1" => "Gobi",
            "2" => "Kalahari",
            "3" => "Arctic"
        ],
        "correct" => "Sahara"
    ],
    [
        "question" => "Which fruit is yellow and curved?",
        "options" => [
            "0" => "Apple",
            "1" => "Orange",
            "2" => "Banana",
            "3" => "Grapes"
        ],
        "correct" => "Banana"
    ],
    [
        "question" => "What is the name of the toy cowboy in Toy Story?",
        "options" => [
            "0" => "Buzz",
            "1" => "Rex",
            "2" => "Woody",
            "3" => "Hamm"
        ],
        "correct" => "Woody"
    ]
];

$prize = array(
    1 => "100",
    2 => "300",
    3 => "500",
    4 => "1000",
    5 => "2000",
    6 => "3000",
    7 => "5000",
    8 => "10000",
    9 => "20000",
    10 => "30000",
    11 => "50000",
    12 => "100000",
    13 => "250000",
    14 => "500000",
    15 => "1000000"
);


$scoreboard = [];

do {
    echo "\nAre you ready to play Who Wants to Be a Millionaire?\n\n";
    

    $name = readline("Please enter your name to start - ");
    echo "Let's start the game, " . $name . "!\n\n";


    $winnings = gameQuestions($questionnaire, $prize);
    $scoreboard[$name] = $winnings;


    $valid_choice = false;
    while (!$valid_choice) {
        echo "\nWhat would you like to do next?\n";
        echo "1. Play again\n";
        echo "2. View scoreboard\n";
        echo "3. Exit\n";

        $choice = readline("Please enter your choice (1, 2, or 3): ");
        $exit = false;

        switch ($choice) {
            case "1":
                $valid_choice = true;
                break;
            case "2":
                displayScoreboard($scoreboard);
                $valid_choice = true;
                break;
            case "3":
                echo "Thank you for playing! Goodbye!\n";
                $valid_choice = true;
                $exit = true;
                break;
            default:
                echo "Invalid choice. Please enter 1, 2, or 3.\n";
        }
    }
} while ($exit != true);


function gameQuestions(array $questions, array $prize)
{
    $is_correct = "true";
    $correct_count = 0;

    shuffle($questions);

    for ($i = 0; $i < count($questions); $i++) {

        echo "Question №" . $i + 1 . " is: \n";
        echo $questions[$i]["question"] . "\n";

        shuffle($questions[$i]["options"]);
        $correct_answer = array_search($questions[$i]["correct"], $questions[$i]["options"]);

        echo $correct_answer;

        for ($j = 0; $j < count($questions[$i]["options"]); $j++) {
            $letter = chr(65 + $j);
            echo $letter . ") " . $questions[$i]["options"][$j] . "\n";
        }

        $player_answer = strtoupper(readline());


        if ($player_answer === chr(65 + $correct_answer)) {
            echo "\nCorrect!\n";
            $correct_count++;
        } else if ($correct_count == 0) {
            echo "Incorrect. The correct answer was " . chr(65 + $correct_answer) . ")\n";
            echo "Unfortunately, you did not get any answers right and you are leaving the game with 0!";
            $is_correct = false;

            return 0;
        } else {
            echo "Incorrect. The correct answer was " . chr(65 + $correct_answer) . ")\n";
            echo "You leave the game with " . $correct_count . " correct answers and the sum of: " . $prize[$correct_count] . "!\n";
            $is_correct = false;

            break;
        }

        if ($correct_count === count($questions)) {
            echo "Congratulations! You answered all questions correctly!\n";
        }
    }

    return $prize[$correct_count];
}

function displayScoreboard(array $scoreboard)
{

    echo "The current scoreboard for players is:\n";

    foreach ($scoreboard as $name => $sum) {
        echo "\nPlayer: " . $name . " won the sum of: " . $sum;
    }
}
