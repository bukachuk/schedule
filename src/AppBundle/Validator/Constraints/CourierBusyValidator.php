<?php
namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use AppBundle\Entity\Routes;

class CourierBusyValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CourierBusy) {
            throw new UnexpectedTypeException($constraint, CourierBusy::class);
        }

        if (!$value instanceof Routes) {
            throw new UnexpectedTypeException($value, Routes::class);
        }

        if($this->em->getRepository('AppBundle:Routes')->isCourierBusy($value)){
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}