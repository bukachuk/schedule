<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Courier;
use Doctrine\ORM\EntityManagerInterface;

/**
 * CourierRepository
 */
class CourierRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Courier::class));
    }

    public function getPaginate()
    {
        return $this->createQueryBuilder('c')->orderBy('c.id', "DESC")->getQuery();
    }
}
