<?php
namespace Prolong\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserRoleType
 * @package Prolong\UserBundle\Form
 */
class UserRoleType extends AbstractType
{
    const FORM_NAME = 'userRole';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', ['required' => false])
            ->add('role', 'text', ['required' => true]);

    }

    public function getName()
    {
        return self::FORM_NAME;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Prolong\UserBundle\Entity\UserRole',
            'csrf_protection' => false,
            'csrf_field_name' => '_token',
        ]);
    }
}
