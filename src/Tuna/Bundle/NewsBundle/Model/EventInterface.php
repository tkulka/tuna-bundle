<?php

namespace TheCodeine\NewsBundle\Model;

interface EventInterface extends NewsInterface
{
    /**
     * @param \DateTime $startDate
     *
     * @return self
     */
    public function setStartDate(\DateTime $startDate = null);

    /**
     * @return \DateTime
     */
    public function getStartDate();

    /**
     * @param \DateTime $endDate
     *
     * @return self
     */
    public function setEndDate(\DateTime $endDate = null);

    /**
     * @return \DateTime
     */
    public function getEndDate();
}
