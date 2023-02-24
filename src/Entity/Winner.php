<?php

namespace App\Entity;

class Winner
{
    
    private array $grid;


    public function __construct(array $grid)
    {
        $this->grid = $grid;
    }

    public function getWinner(): ?string
    {
        $rows = $this->checkRowsColumns();
        $columns = $this->checkRowsColumns(false);
        $leftDiagonal = $this->checkDiagonals();
        $rightDiagonal = $this->checkDiagonals(false);

        $winnersArray = [$rows, $columns, $leftDiagonal, $rightDiagonal];

        foreach ($winnersArray as $winnerSymbol) {
            if ($winnerSymbol) {
                return $winnerSymbol;
            }
        }

        return null;
    }

    private function checkRowsColumns(bool $isRow = true): ?string
    {
        for ($row = 0; $row < 3; $row ++) {
            $cell = '';
            for ($column = 0; $column < 3; $column ++) {
                if ($isRow) {
                    $cell = $cell . $this->grid[$row][$column];
                } else {
                    $cell = $cell . $this->grid[$column][$row];
                }
            }

            if (!is_null($this->getWinningSymbol($cell))) {
                return $this->getWinningSymbol($cell);
            }
        }

        return null;
    }

    private function checkDiagonals(bool $isLeft = true): ?string
    {
        $cell = '';
        for ($i = 0; $i < 3; $i ++) {
            if ($isLeft) {
                $cell = $cell . $this->grid[2 - $i][$i];
            } else {
                $cell = $cell . $this->grid[$i][$i];
            }
        }

        if (!is_null($this->getWinningSymbol($cell))) {
            return $this->getWinningSymbol($cell);
        }

        return null;
    }

    private function getWinningSymbol(string $tripleSymbol): ?string
    {
        $symbolsMap = [
            'XXX' => 'X',
            'OOO' => 'O'
        ];

        if (isset($symbolsMap[$tripleSymbol])) {
            return $symbolsMap[$tripleSymbol];
        }

        return null;
    }

    public function checkDraw(bool $fulfilled): bool
    {
        $winner = $this->getWinner();

        return !$winner && $fulfilled;
    }
    
    public function checkGridFulfilled(): bool
    {
        $grid = $this->grid;
        for ($row = 0; $row < 3; $row++) {
            for ($column = 0; $column < 3; $column++) {
                if (!is_null($grid[$row][$column])) {
                    return false;
                }
            }
        }

        return true;
    }
    
}