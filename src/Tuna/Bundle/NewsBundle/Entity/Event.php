<?php

namespace TheCodeine\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TheCodeine\NewsBundle\Model\EventInterface;

/**
 * Event
 *
 * @ORM\Entity(repositoryClass="TheCodeine\NewsBundle\Entity\NewsRepository")
 */
class Event extends AbstractNews implements EventInterface
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime")
     *
     * @Assert\Date()
     * @Assert\NotNull()
     */
    protected $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime")
     *
     * @Assert\Date()
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

        $this->setStartDate($startDate);
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate(\DateTime $startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}