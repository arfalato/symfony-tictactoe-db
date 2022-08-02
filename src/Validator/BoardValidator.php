<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

class BoardValidator
{
    private $validator;

    private $constraints;

    public function __construct()
    {
        $this->validator = Validation::createValidator();

        $this->constraints = new Assert\Collection([
            'row' => [
                new Assert\Range([
                    'min' => 0,
                    'max' => 2
                ],  'row invalid value'),
            ],
            'column' => [
                new Assert\Range(['min' => 0, 'max' => 2], 'column invalid value'),
            ],
            'symbol' => [
                new Assert\Type('string'),
                new Assert\NotBlank(['message' => 'empty symbol']),
                new Assert\NotNull(),
                new Assert\Regex([
                    'pattern' => '/[XO]/',
                    'message' => "Not allowed symbol: just 'X' or 'O'"
                ])
            ],
        ]);
    }

    public function validateParams(array $params) : array
    {
        $validation['error'] = [];
        $violations = $this->validator->validate($params, $this->constraints);
        if (!$violations instanceof ConstraintViolationList) {
            throw new \RuntimeException('Invalid violations type.');
        }
        if ($violations->count() > 0) {

            foreach ($violations as $violation) {
                $validation['error'][] = $violation->getMessage();
            }
        }

        if (
            (isset($params['row']) && !empty($params['row']) && !is_int($params['row']))
            ||
            (isset($params['column']) && !empty($params['column']) && !is_int($params['column']))
        ) {
            array_push($validation['error'], 'invalid value not integer');
        }

        return $validation;
    }
}