<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CourierBusy extends Constraint
{
    public $message = 'routes.busy';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}