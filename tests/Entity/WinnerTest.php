<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Winner;

class WinnerTest  extends TestCase
{

    /**
     * @dataProvider boardDataProvider
     */
    public function testWinner(array $board) : void
    {
        $winner = new Winner($board['board']);
        $this->assertEquals($board['expected'], $winner->getWinner());
    }

    public function boardDataProvider() : array
    {
        return [
            [['board' => [
                ['X','O', null],
                ['O','X','X'],
                [null,'O','X']
            ], 'expected' => 'X']],
            [['board' => [
                ['O','O','O'],
                [null, null, null],
                ['X','O','X']
            ],  'expected' => 'O']],
            [['board' => [
                ['O','X', null],
                ['X','O', null],
                [null,'O', 'O']
            ],  'expected' => 'O']],
            [['board' => [
                ['O','X', null],
                ['X','X','O'],
                ['O','X','X']
            ],  'expected' => 'X']],
            [['board' => [
                ['X',null, null],
                ['X','O', null],
                ['X','O', null]
            ],  'expected' => 'X']],
        ];
    }

    /**
     * @dataProvider boardDrawDataProvider
     */
    public function testDraw(array $boardDraw) : void
    {
        $winner = new Winner($boardDraw['board']);
        $this->assertEquals($boardDraw['expected'], $winner->checkDraw(true));
    }

    public function boardDrawDataProvider() : array
    {
        return [
            [['board' => [
                ['X','O','O'],
                ['O','X','X'],
                ['X','O','X']
            ], 'expected' => false]],
            [['board' => [
                ['X','O','X'],
                ['O','O','X'],
                ['X','X','O']
            ],  'expected' => true]],
        ];
    }
}