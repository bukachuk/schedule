<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Routes;
use AppBundle\Filter\RoutesFilter;
use Doctrine\ORM\EntityManagerInterface;

/**
 * RouteRepository
 */
class RoutesRepository extends \Doctrine\ORM\EntityRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Routes::class));
    }

    public function getPaginate(?RoutesFilter $routesFilter = null)
    {
        $qb = $this->createQueryBuilder('c')->orderBy('c.id', "DESC");

        if($routesFilter) {
            if ($routesFilter->getDateFrom()) {
                $qb->andWhere('DATE(c.date) >= :dateFrom')
                    ->setParameter('dateFrom', $routesFilter->getDateFrom());
            }

            if ($routesFilter->getDateTo()) {
                $qb->andWhere('DATE(c.date) <= :dateTo')
                    ->setParameter('dateTo', $routesFilter->getDateTo());
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function isCourierBusy(Routes $routes){
        if(!$routes->getRegion()){
            return null;
        }

        $returnDate = clone $routes->getDate();
        $returnDate->modify('+ ' . $routes->getRegion()->getDelivery() . ' days');

        $qb = $this->createQueryBuilder('c')
            ->where('c.courier = :courier ')
            ->andWhere('(DATE(c.date) > :date AND DATE(c.date) < :returnDate) OR (DATE(c.date) < :date AND DATE(c.returnDate) > :date) OR(c.date = :date)')
            ->setParameter('courier', $routes->getCourier())
            ->setParameter('date', $routes->getDate())
            ->setParameter('returnDate', $returnDate)
            ->orderBy('c.date', "DESC");

        return $qb->getQuery()->getResult();
    }
}
