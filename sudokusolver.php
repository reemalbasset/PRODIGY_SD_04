<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sudokusolver.css">
    <title>Sudoku Solver</title>
</head>
<body>
<h2>Sudoku Solver</h2>
<div class="container">

<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["solve"])) {
        $sudokuGrid = [];

        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $cellValue = isset($_POST["cell_${i}_${j}"]) ? intval($_POST["cell_${i}_${j}"]) : 0;
                $sudokuGrid[$i][$j] = $cellValue;
            }
        }

        if (solveSudoku($sudokuGrid)) {
            echo "<h3>Solved Sudoku:</h3>";
            displaySudoku($sudokuGrid);
        } else {
            echo "<p>Could not solve the Sudoku puzzle. Invalid input or unsolvable puzzle.</p>";
        }
    }
}

function solveSudoku(&$grid) {
    $emptyCell = findEmptyCell($grid);

    if ($emptyCell[0] == -1) {
        return true; // Puzzle solved
    }

    list($row, $col) = $emptyCell;

    for ($num = 1; $num <= 9; $num++) {
        if (isSafe($grid, $row, $col, $num)) {
            $grid[$row][$col] = $num;

            if (solveSudoku($grid)) {
                return true; // Successfully solved
            }

            $grid[$row][$col] = 0; // Backtrack
        }
    }

    return false; // No solution found
}

function findEmptyCell($grid) {
    foreach ($grid as $row => $values) {
        foreach ($values as $col => $value) {
            if ($value == 0) {
                return [$row, $col];
            }
        }
    }
    return [-1, -1]; // Return a default value indicating no empty cell found
}

function isSafe($grid, $row, $col, $num) {
    // Check if the number is not in the same row or column
    for ($i = 0; $i < 9; $i++) {
        if ($grid[$row][$i] == $num || $grid[$i][$col] == $num) {
            return false;
        }
    }

    // Check if the number is not in the same 3x3 subgrid
    $startRow = $row - $row % 3;
    $startCol = $col - $col % 3;

    for ($i = 0; $i < 3; $i++) {
        for ($j = 0; $j < 3; $j++) {
            if ($grid[$startRow + $i][$startCol + $j] == $num) {
                return false;
            }
        }
    }

    return true;
}

function displaySudoku($grid) {
    echo "<table>";
    for ($i = 0; $i < 9; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 9; $j++) {
            echo "<td>{$grid[$i][$j]}</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

?>

<form method="post" action="">
    <label>Enter Sudoku grid:</label>
    <table>
        <?php
        for ($i = 0; $i < 9; $i++) {
            echo "<tr>";
            for ($j = 0; $j < 9; $j++) {
                echo "<td><input type='number' name='cell_${i}_${j}' min='0' max='9'></td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    <br><br>
    <input type="submit" name="solve" value="Solve Sudoku">
</form>
</div>

</body>
</html>

