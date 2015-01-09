<?php

namespace Prolong\UserBundle\Entity;

use DateTime;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User role
 *
 * @ORM\Table(name="user_role")
 * @ORM\Entity(repositoryClass="Prolong\UserBundle\Entity\UserRoleRepository")
 */
class UserRole implements RoleInterface
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_CUSTOMER = 'ROLE_CUSTOMER';
    const ROLE_AUTHOR = 'ROLE_AUTHOR';
    const ROLE_PUBLISHER = 'ROLE_PUBLISHER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     *
     * @var string
     */
    protected $role;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", name="createdAt")
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Set user role id
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get user role id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user role name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name role
     *
     * @param string $value
     *
     * @return $this
     */
    public function setName($value)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Get created time role
     *
     * @return DateTime A DateTime object.
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Constructor UserRole
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * Get role
     *
     * @return string.
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set Role
     *
     * @param $role string
     *
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }
}
