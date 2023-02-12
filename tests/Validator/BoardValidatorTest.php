<?php

namespace App\Tests\Validator;

use PHPUnit\Framework\TestCase;
use App\Validator\BoardValidator;

class BoardValidatorTest extends TestCase
{

    /**
     * @dataProvider invalidSymbolDataProvider
     */
    public function testNotAllowedSymbol(array $invalidSymbol): void
    {
        $validator = new BoardValidator();
        $actual = $validator->validateParams($invalidSymbol);
        $this->assertEquals("Not allowed symbol: just 'X' or 'O'", $actual['error'][0]);
    }

    public function invalidSymbolDataProvider(): array
    {
        return [[
            ['symbol' => 'U' , 'row' => 0 , 'column' => 0],
            ['symbol' => 'Z' , 'row' => 0 , 'column' => 1],
        ]];
    }

    public function testEmptySymbol(): void
    {
        $validator = new BoardValidator();
        $actual = $validator->validateParams(['symbol' => '' , 'row' => 0 , 'column' => 0]);
        $this->assertEquals("empty symbol", $actual['error'][0]);
    }

    /**
     * @dataProvider invalidCoordinatesDataProvider
     */
    public function testInvalidCoordinates(array $expectedResult, array $input): void
    {
        $validator = new BoardValidator();
        $actual = $validator->validateParams($input);

        $this->assertEquals($expectedResult, $actual);
    }

    public function invalidCoordinatesDataProvider(): array
    {
        return [
            [
                ['error' => ["This value should be a valid number."]],
                ['symbol' => 'X' , 'row' => '' , 'column' => 0]
            ],
            [
                ['error' => ["This value should be a valid number."]],
                ['symbol' => 'X' , 'row' => 0 , 'column' => '']
            ],
            [
                ['error' => ["This field is missing."]],
                ['symbol' => 'X' , 'column' => 0]
            ],
            [
                ['error' => ["This field is missing."]],
                ['symbol' => 'X' , 'row' => 0],
            ],
            [
                ['error' => ["row invalid value"]],
                ['symbol' => 'X' , 'row' => -1 , 'column' => 0],
            ],
            [
                ['error' => ["row invalid value", "column invalid value"]],
                ['symbol' => 'X' , 'row' => -1 , 'column' => -1]
            ],
            [
                ['error' => ["row invalid value"]],
                ['symbol' => 'X' , 'row' => 3 , 'column' => 0],
            ],
            [
                ['error' => ["column invalid value"]],
                ['symbol' => 'X' , 'row' => 0 , 'column' => 3],
            ],
            [
                ['error' => ["invalid value not integer"]],
                ['symbol' => 'X' , 'row' => 1.5 , 'column' => 0],
            ],
            [
                ['error' => ["invalid value not integer"]],
                ['symbol' => 'X' , 'row' => 1 , 'column' => 0.5],
            ],
        ];
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testValid(array $valid): void
    {
        $validator = new BoardValidator();
        $this->assertEquals(['error' => []], $validator->validateParams($valid));
    }

    public function validDataProvider(): array
    {
        return [[
            ['symbol' => 'X' , 'row' => 0 , 'column' => 0],
            ['symbol' => 'O' , 'row' => 0 , 'column' => 1],
        ]];
    }
}