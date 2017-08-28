<?php

namespace PhpSolution\ApiUserBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * ChangePasswordFormType
 */
class ChangePasswordFormType extends UserBaseFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('currentPassword', PasswordType::class, [
            'mapped' => false,
            'constraints' => new UserPassword()
        ]);
        $builder->add('plainPassword', RepeatedType::class, ['type' => PasswordType::class]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('method', Request::METHOD_POST)
            ->setDefault('validation_groups', ['ChangePassword', 'Default']);
    }
}