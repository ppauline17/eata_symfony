<?php

namespace App\Form;

use App\Entity\Price;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('halfDay', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La valeur doit être égale ou supérieure à zéro.',
                    ]),
                ],
            ])
            ->add('fullDay', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La valeur doit être égale ou supérieure à zéro.',
                    ]),
                ],
            ])
            ->add('morning', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La valeur doit être égale ou supérieure à zéro.',
                    ]),
                ],
            ])
            ->add('eveningFirstHour', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La valeur doit être égale ou supérieure à zéro.',
                    ]),
                ],
            ])
            ->add('eveningHalfHour', MoneyType::class, [
                'invalid_message' => 'Veuillez saisir un nombre',
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La valeur doit être égale ou supérieure à zéro.',
                    ]),
                ],
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
