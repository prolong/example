<?php
namespace Prolong\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use Prolong\UserBundle\Entity\User;
use Prolong\UserBundle\Entity\UserRepository;
use Prolong\UserBundle\Entity\UserRole;
use Prolong\UserBundle\Entity\UserRoleRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class UserService
 * @package Prolong\UserBundle\Service
 */
class UserService
{
    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var  AuthorizationChecker */
    protected $authorizationChecker;

    /** @var  UserPasswordEncoder */
    protected $userPasswordEncoder;

    /** @var UserRepository */
    protected $userRepository;

    /** @var  UserRoleRepository */
    protected $userRoleRepository;

    /** @var  EntityManager */
    protected $em;

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $tokenStorage
     * @param \Symfony\Component\Security\Core\Authorization\AuthorizationChecker $authorizationChecker
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder $userPasswordEncoder
     * @param \Prolong\UserBundle\Entity\UserRepository $userRepository
     * @param \Prolong\UserBundle\Entity\UserRoleRepository $userRoleRepository
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(
        TokenStorage $tokenStorage,
        AuthorizationChecker $authorizationChecker,
        UserPasswordEncoder $userPasswordEncoder,
        UserRepository $userRepository,
        UserRoleRepository $userRoleRepository,
        EntityManager $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->userRepository = $userRepository;
        $this->userRoleRepository = $userRoleRepository;
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return bool|User
     * @throws \Exception
     */
    public function login(User $user)
    {
        $password = $this->userPasswordEncoder->encodePassword($user,
            $user->getPassword());

        /** @var User $entity */
        $entity = $this->userRepository->findOneBy(
            [
                'email' => $user->getEmail(),
                'password' => $password
            ]
        );

        if (!$entity) {
            return null;
        }

        $this->auth($entity);

        return $entity;
    }

    /**
     * @param User $user
     * @return bool|User
     * @throws \Exception
     */
    public function register(User $user)
    {
        $entity = $this->userRepository->findOneBy(
            [
                'email' => $user->getEmail()
            ]
        );

        if ($entity) {
            return null;
        }


        $password = $this->userPasswordEncoder->encodePassword($user,
            $user->getPassword());

        $user->setPassword($password);


        $role = $this->userRoleRepository->findOneBy(
            ['role' => UserRole::ROLE_CUSTOMER]
        );

        if ($role && !$user->getCollectionOfRoles()->contains($role)) {
            $user->addRole($role);
        }

        $this->em->persist($user);
        $this->em->flush();

        $this->auth($user);

        return $user;
    }

    /**
     * @param User $user
     * @return null|object
     */
    public function addRole(User $user)
    {
        $entity = $this->userRepository->findOneBy(
            [
                'email' => $user->getEmail()
            ]
        );

        if (!$entity) {
            return null;
        }

        foreach ($user->getRoles() as $role) {
            /** @var UserRole $userRole */
            $userRole = $this->userRoleRepository->findOneBy(
                ['role' => $role]
            );

            if ($userRole) {
                $entity->addRole($userRole);
            }
        }

        $this->em->flush();

        return $entity;
    }

    /**
     * @param User $user
     * @return null|User
     */
    public function removeRole(User $user)
    {
        /** @var User $entity */
        $entity = $this->userRepository->findOneBy(
            [
                'email' => $user->getEmail()
            ]
        );

        if (!$entity) {
            return null;
        }

        foreach ($user->getRoles() as $role) {
            /** @var UserRole $userRole */
            $userRole = $this->userRoleRepository->findOneBy(
                ['role' => $role]
            );

            if ($userRole) {
                $entity->removeRole($userRole);
            }
        }

        $this->em->flush();

        return $entity;
    }

    /**
     * Auth user
     *
     * @param User $user
     */
    public function auth(User $user)
    {
        $token = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'main',
            $user->getRoles()
        );

        $this->tokenStorage->setToken($token);
    }

    /**
     * @return bool|User
     */
    public function getUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user || !($user instanceof User)) {
            return false;
        }

        return $user;
    }
}
