<?php
namespace Prolong\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserUpdateRoleType
 * @package Prolong\UserBundle\Form
 */
class UserUpdateRoleType extends AbstractType
{
    const FORM_NAME = 'userUpdateRole';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', ['required' => true])
            ->add('roles', 'collection', [
                'type' => new UserRoleType(),
                'required' => true,
                'allow_add' => true,
                'allow_delete' => true
            ]);
    }

    public function getName()
    {
        return self::FORM_NAME;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Prolong\UserBundle\Entity\User',
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
        ]);
    }
}
