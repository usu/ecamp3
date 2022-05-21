<?php

namespace App\Validator\ColumnLayout;

use App\Entity\ContentNode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AssertNoOrphanChildrenValidator extends ConstraintValidator {
    public function validate($value, Constraint $constraint) {
        if (!$constraint instanceof AssertNoOrphanChildren) {
            throw new UnexpectedTypeException($constraint, AssertNoOrphanChildren::class);
        }

        /** @var ContentNode $contentNode */
        $contentNode = $this->context->getObject();

        if (!($contentNode instanceof ContentNode && 'ColumnLayout' === $contentNode->getContentTypeName())) {
            throw new InvalidArgumentException('AssertNoOrphanChildren is only valid inside a ColumnLayout object');
        }

        $slots = array_map(function ($col) {
            if (isset($col['slot'])) {
                return $col['slot'];
            }

            return null;
        }, $value['columns']);

        $childSlots = $contentNode->children->map(function (ContentNode $child) {
            return $child->slot;
        })->toArray();

        $orphans = array_unique(array_diff($childSlots, $slots));

        if (count($orphans)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ slots }}', join(', ', $orphans))
                ->addViolation()
            ;
        }
    }
}
