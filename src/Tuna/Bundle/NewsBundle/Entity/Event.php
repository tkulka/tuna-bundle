<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Repository\NewsRepository")
 *
 */
class Event extends News
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
     * @Assert\NotNull()
     * @ORM\Column(name="end_date", type="datetime")
     */
    protected $endDate;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setCreatedAt(new \DateTime());
        $this->setStartDate(new \DateTime());
    }

    /**
     * @return $this
     * @param \DateTime $startDate
     */
    public function setStartDate($startDate)
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
     * @return $this
     * @param \DateTime $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }
}
