<?php

namespace App\Form\Constraints;

use App\Dto\ProjectFilter;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BetweenValidator extends ConstraintValidator
{

    public function validate(mixed $value, Constraint $constraint)
    {
        if ($value === null) {
            return;
        }

        if (!$constraint instanceof Between) {
            throw new NotFoundHttpException();
        }

        $operator = $constraint->operator;
        /** @var ProjectFilter */
        $entity = $this->context->getObject()->getParent()->getData();
        $target = $constraint->target;

        if (property_exists($entity, $target)) {
            $target = $entity->$target;
            if ($target === null) {
                return;
            }
            $message = $constraint->message;
            if (str_contains($message, '%value%')) {
                $message = str_replace('%value%', number_format($target, 0, ',', ' '), $message);
            }
            if ($operator == Between::LOWER and $target < $value) {
                $this->context->buildViolation($message)->addViolation();
            }
            if ($operator == Between::UPPER and $target > $value) {
                $this->context->buildViolation($message)->addViolation();
            }
            return;
        }
        throw new Exception("Property " . $target . ' does not exist in class ' . get_class($entity));
    }
}
