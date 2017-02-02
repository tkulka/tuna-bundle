<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Entity\NewsRepository")
 */
class Event extends AbstractNews
{
    /**
     * @var \DateTime
     *
     * @Assert\Date()
     * @Assert\NotNull()
     * @ORM\Column(name="start_date", type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @Assert\Date()
     * @ORM\Column(name="end_date", type="datetime")
     */
    protected $endDate;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $startDate = new \DateTime();
        $startDate->setTime(0, 0, 0);
        $startDate->format('Y-m-d');

        $this->setCreatedAt(new \DateTime());
        $this->setStartDate($startDate);
    }

    /**
     * @param \DateTime $startDate
     *
     * @return Event
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     *
     * @return Event
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}
