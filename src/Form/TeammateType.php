<?php

namespace App\Form;

use App\Entity\Teammate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeammateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add('job')
            ->add('hierarchy', ChoiceType::class, [
                'choices' => [
                    'Directeur(trice) / Président(e)' => 1,
                    'Directeur(trice) Adjoint(e) / Président(e) Adjoint(e)' => 2,
                    'Autre' => 3
                ],
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teammate::class,
        ]);
    }
}
