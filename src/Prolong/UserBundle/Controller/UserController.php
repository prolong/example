<?php

namespace Prolong\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use Prolong\UserBundle\Entity\User;
use Prolong\UserBundle\Form\FormLogin;
use Prolong\UserBundle\Service\UserService;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class UserController
 * @package Prolong\UserBundle\Controller
 */
class UserController extends FOSRestController
{

    /**
     * @param Request $request the request object
     * @Rest\View()
     *
     * @return View
     */
    public function loginAction(Request $request)
    {
        /** @var Form $form */
        $form = $this->get('form.user_login');
        $form->handleRequest($request);

        $entity = null;

        if ($form->isValid()) {

            $userService = $this->getUserService();

            /** @var User $entity */
            $entity = $form->getData();

            $entity = $userService->login($entity);

            if (empty($entity)) {
                return View::create(['error' => 'Login failed'],
                    Codes::HTTP_BAD_REQUEST);
            }
        } else {
            return View::create(['error' => 'Invalid argument'],
                Codes::HTTP_BAD_REQUEST);
        }

        $view = View::create();
        $view->setStatusCode(Codes::HTTP_CREATED);
        $view->setData([
            "code" => Codes::HTTP_CREATED,
            "response" => $entity,
        ]);
        $view->setSerializationContext(SerializationContext::create()
            ->setGroups(['register']));

        return $view;
    }

    /**
     * @param Request $request the request object
     * @Rest\View()
     *
     * @return View
     */
    public function registerAction(Request $request)
    {
        /** @var Form $form */
        $form = $this->get('form.user_register');
        $form->handleRequest($request);

        $entity = null;

        if ($form->isValid()) {

            $userService = $this->getUserService();

            /** @var User $entity */
            $entity = $form->getData();

            $entity = $userService->register($entity);

            if (empty($entity)) {
                return View::create(['error' => 'Mail already exists'],
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
            ->setGroups(['register']));

        return $view;
    }

    /**
     * @param Request $request the request object
     * @Rest\View()
     *
     * @return View
     */
    public function meAction(Request $request)
    {

        $session = $request->getSession();

        if (!$session) {
            $session = new Session();
        }

        if (!$session->isStarted()) {
            $session->start();
        }

        $userService = $this->getUserService();

        $user = $userService->getUser();

        if ($user) {
            $view = View::create();
            $view->setStatusCode(Codes::HTTP_CREATED);
            $view->setData([
                'code' => Codes::HTTP_CREATED,
                'response' => $user,
            ]);
            $view->setSerializationContext(SerializationContext::create()
                ->setGroups(['me']));


        } else {
            $view = View::create(['error' => 'Anonymous'],
                Codes::HTTP_BAD_REQUEST);
        }

        return $view;
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->container->get('service.user');
    }
}
