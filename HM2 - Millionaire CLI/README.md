# Who Wants to Be a Millionaire? CLI Game

This is a simple command-line application where players answer multiple-choice questions to win prizes. The game keeps track of the scores while the application is running and displays them upon request.

## How to Run
 **Run the game**: Open your terminal or command prompt, navigate to the directory where `millionaire.php` is saved, and execute the following command:
    ```
    php millionaire.php
    ```

## How to Play

1. **Start the game**: Enter your name to start.
2. **Answer questions**: You will be presented with multiple-choice questions. Enter the letter corresponding to your answer.
3. **View your winnings**: Correct answers will earn you prizes, with the prize amount increasing with each correct answer.
4. **Decide next steps**: After finishing the game, you can choose to play again, view the scoreboard, or exit.

## Functions

- `gameQuestions(array $questions, array $prize)`: Handles the quiz logic, shuffles questions and options, and calculates winnings based on correct answers.
- `displayScoreboard(array $scoreboard)`: Displays the current scoreboard showing player names and their winnings.

## Example Run

Are you ready to play Who Wants to Be a Millionaire?

Please enter your name to start - Ivan
Let's start the game, Ivan!

Question №1 is:
What is the tallest mountain in the world?
- **A)** K2
- **B)** Mount Everest
- **C)** Kangchenjunga
- **D)** Lhotse

**[Your Answer]**

Correct!
Question №2 is: 
Which of the following is a reptile?
- **A)** Frog
- **B)** Hawk
- **C)** Rabbit
- **D)** Turtle

**[Your Answer - C]**

Incorrect. The correct answer was D)
You leave the game with 1 correct answers and the sum of: 100!

What would you like to do next?

1. Play again
2. View scoreboard
3. Exit

**[Your Choice]**

