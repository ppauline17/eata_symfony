<?php

namespace App\Form;

use App\Entity\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('halfDay', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre'
            ])
            ->add('fullDay', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre'
            ])
            ->add('morning', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre'
            ])
            ->add('eveningFirstHour', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre'
            ])
            ->add('eveningHalfHour', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Price::class,
        ]);
    }
}
