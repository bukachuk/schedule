<?php
namespace AppBundle\Filter;
use DateTime;

class RoutesFilter {

    private $dateFrom;
    private $dateTo;

    /**
     * @return mixed
     */
    public function getDateFrom() : ?DateTime
    {
        return $this->dateFrom;
    }

    /**
     * @param mixed $dateFrom
     */
    public function setDateFrom(DateTime $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    /**
     * @return mixed
     */
    public function getDateTo() : ?DateTime
    {
        return $this->dateTo;
    }

    /**
     * @param mixed $dateTo
     */
    public function setDateTo(DateTime $dateTo)
    {
        $this->dateTo = $dateTo;
    }
}