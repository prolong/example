<?php

namespace Prolong\UserBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Prolong\UserBundle\Entity\UserRepository")
 */
class User implements UserInterface
{
    const SALT = '34fh^ccvfRoLk0dx!f8U3bpLeH';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     *
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     *
     * @var string
     */
    protected $password;

    /**
     * @ORM\ManyToMany(targetEntity="UserRole")
     * @ORM\JoinTable(name="user_role_assoc",
     *     joinColumns={@ORM\JoinColumn(name="userId", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="roleId", referencedColumnName="id")}
     * )
     *
     * @var ArrayCollection
     */
    protected $roles = [];

    /**
     * @ORM\Column(name="createdAt", type="datetime", nullable=false)
     *
     * @var DateTime $createdAt
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updatedAt", type="datetime", nullable=false)
     *
     * @var DateTime $updatedAt
     */
    protected $updatedAt;

    /**
     * Constructor User
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();

        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * Get salt for password
     *
     * @return string
     */
    public function getSalt()
    {
        return self::SALT;
    }

    /**
     * @return bool
     */
    public function eraseCredentials()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getEmail();
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

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user email
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get user password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set user password
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Add role for user
     *
     * @param UserRole $role
     *
     * @return $this
     */
    public function addRole(UserRole $role)
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(UserRole $role)
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getCollectionOfRoles()
    {
        return $this->roles;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        $roles = [];

        /** @var UserRole $roleItem */
        foreach ($this->roles as $roleItem) {
            $roles[] = $roleItem->getRole();
        }

        return $roles;
    }


    /**
     * Set user roles
     *
     * @param ArrayCollection $roles
     *
     * @return $this
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get user created time
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user created time
     *
     * @param DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get user updated time
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set user updated time
     *
     * @param DateTime $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
