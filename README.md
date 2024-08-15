# CLI Calculation Scripts - Lecture 1

This repository contains two PHP scripts designed to perform basic arithmetic operations and geometric calculations via the command line. The user needs to specify the type of operation, the operator and the variables to be used.

# Script 1: Basic Arithmetic Operations
This script performs basic arithmetic operations such as addition, subtraction, multiplication, division, modulo, and exponentiation.

# Usage
You can run the script using the following command:

php script1.php --type=operation --operator=<operator> --param1=<value1> --param2=<value2>

# Options
- **`--type`**: Specifies the type of operation (required, should be `"operation"`).
- **`--operator`**: The operator for the calculation (required). Available operators:
  - `plus`: Addition
  - `minus`: Subtraction
  - `multiply`: Multiplication
  - `divide`: Division
  - `modulo`: Modulo
  - `exponentiation`: Exponentiation
- **`--param1`**: The first operand (required).
- **`--param2`**: The second operand (required).

# Script 2: Geometric Shape Calculations
This script calculates the area and perimeter of triangles, squares, and circles.

# Usage
You can run the script using the following command:

php script2.php --type=geometry --calculation=<calculation> --shape=<shape> --radius=<radius> [--a=<side1>] [--b=<side2>] [--c=<side3>]

# Options
- **`--type`**: Specifies the type of calculation (required, should be `"geometry"`).
- **`--calculation`**: The type of calculation to perform (required). Available options:
  - `area`: Calculate the area
  - `perimeter`: Calculate the perimeter
- **`--shape`**: The geometric shape (required). Available shapes:
  - `triangle`
  - `square`
  - `circle`
- **`--radius`**: The radius (for circles and squares) or height (for triangles) (required).
- **`--a`, `--b`, `--c`**: Side lengths for the triangle (optional, required if shape is `"triangle"`).
