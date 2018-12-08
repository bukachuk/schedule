<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;

/**
 * RegionRepository
 */
class RegionRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Region::class));
    }

    public function getPaginate()
    {
        return $this->createQueryBuilder('c')->orderBy('c.id', "DESC")->getQuery();
    }

    public function search($search)
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :name')
            ->setParameter('name', '%' . $search . '%')
            ->orderBy('c.name', "ASC")
            ->getQuery()
            ->getResult();
    }
}
