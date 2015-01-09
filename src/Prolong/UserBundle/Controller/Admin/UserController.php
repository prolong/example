<?php

namespace Prolong\UserBundle\Controller\Admin;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Prolong\UserBundle\Entity\User;
use Prolong\UserBundle\Service\UserService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class UserController
 * @package Prolong\UserBundle\Controller\Admin
 */
class UserController extends FOSRestController
{
    const MODE_ADD_ROLE = 1;
    const MODE_REMOVE_ROLE = 2;

    /**
     * @param Request $request the request object
     * @Rest\View()
     *
     * @return View
     */
    public function addRoleAction(Request $request)
    {
        return $this->updateRole($request, self::MODE_ADD_ROLE);
    }

    /**
     * @param Request $request the request object
     * @Rest\View()
     *
     * @return View
     */
    public function removeRoleAction(Request $request)
    {
        return $this->updateRole($request, self::MODE_REMOVE_ROLE);
    }

    /**
     * @param Request $request the request object
     * @Rest\View()
     *
     * @param $mode
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function updateRole(Request $request, $mode)
    {
        /** @var Form $form */
        $form = $this->get('form.user_update_role');
        $form->handleRequest($request);

        $entity = null;

        if ($form->isValid()) {

            /** @var UserService $userService */
            $userService = $this->container->get('service.user');

            /** @var User $entity */
            $entity = $form->getData();

            if ($mode == self::MODE_ADD_ROLE) {
                $entity = $userService->addRole($entity);
            } else {
                $entity = $userService->removeRole($entity);
            }

            if (empty($entity)) {
                return View::create(['error' => 'User not found'],
                    Codes::HTTP_BAD_REQUEST);
            }
        } else {
            return View::create(['error' => 'Invalid argument'],
                Codes::HTTP_BAD_REQUEST);
        }

        $view = View::create();
        $view->setStatusCode(Codes::HTTP_CREATED);
        $view->setData([
            'code' => Codes::HTTP_CREATED,
            'response' => $entity,
        ]);
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(['update_role']));

        return $view;
    }
}
