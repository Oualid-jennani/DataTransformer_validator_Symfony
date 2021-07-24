<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class ConstraintDateDiffValidator extends ConstraintValidator
{
    /**
     * @param mixed      $object      The object being validated
     * @param Constraint $constraint The constraint to be analysed
     */
    public function validate($object, Constraint $constraint)
    {
        if ($object->getFromDate() > $object->getToDate()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value1 }}', $object->getFromDate()->format('Y-m-d H:i:s'))
                ->setParameter('{{ value2 }}', $object->getToDate()->format('Y-m-d H:i:s'))
                ->addViolation();
        }
    }
}

