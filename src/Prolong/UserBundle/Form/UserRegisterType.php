<?php
namespace Prolong\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class UserRegisterType
 * @package Prolong\UserBundle\Form
 */
class UserRegisterType extends AbstractType
{
    const FORM_NAME = 'userRegister';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', ['required' => true])
            ->add('password', 'text', ['required' => true]);
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
