<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Route
 *
 * @ORM\Table(name="route")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RoutesRepository")
 * @AppAssert\CourierBusy
 * @ORM\HasLifecycleCallbacks()
 */
class Routes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     * @ORM\Column(name="return_date", type="date")
     */
    private $returnDate;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Courier",cascade={"persist"})
     * @ORM\JoinColumn(name="courier_id", referencedColumnName="id", nullable=true)
     * */
    private $courier;

    /**
     * @Assert\NotBlank(message="region.blank")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Region",cascade={"persist"})
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id", nullable=true)
     * */
    private $region;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getCourier()
    {
        return $this->courier;
    }

    /**
     * @param mixed $courier
     */
    public function setCourier($courier): void
    {
        $this->courier = $courier;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region): void
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getReturnDate(): \DateTime
    {
        return $this->returnDate;
    }

    /**
     * @param string $returnDate
     */
    public function setReturnDate(\DateTime $returnDate): void
    {
        $this->returnDate = $returnDate;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $date = clone $this->date;
        $date->modify('+' . $this->region->getDelivery() . ' days');
        $this->returnDate = $date;
    }
}

