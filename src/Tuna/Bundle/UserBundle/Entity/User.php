<?php

namespace TheCodeine\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User extends FOSUser implements TwoFactorInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $authCode;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function isEmailAuthEnabled()
    {
        return true; // This can also be a persisted field
    }

    public function getEmailAuthCode()
    {
        return $this->authCode;
    }

    public function setEmailAuthCode($authCode)
    {
        $this->authCode = $authCode;
    }
}
