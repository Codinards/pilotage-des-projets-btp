<?php

namespace App\Form\Constraints;

use Symfony\Component\Validator\Constraint;

class DateLimit extends Constraint
{
    const UPPER = '>';
    const LOWER = '<';

    public function __construct(
        public string $target,
        public string $message,
        public ?string $operator = self::LOWER
    ) {
        parent::__construct();
    }
}
