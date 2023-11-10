<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('login')
            ->add('password', HiddenType::class)
            ->add('newPassword', PasswordType::class, [
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Tout' => 'ROLE_ADMIN',
                    'Articles' => 'ROLE_EDITOR',
                    'Equipes' => 'ROLE_TEAM',
                    'Le coin des enfants' => 'ROLE_CHILD',
                ],
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
