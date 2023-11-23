<?php

namespace App\Form;

use App\Entity\Teammate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TeammateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $category_label = $options['category_label'];

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
            ->add('old_picture', HiddenType::class)
        ;

        if($category_label == 'association'){
            $builder 
                ->add('picture', FileType::class, [
                    'data_class' => null,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/jpg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Veuillez télécharger un fichier JPG ou PNG valide.',
                        ]),
                    ],
                ])
                ->add('description');
        };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teammate::class,
            'category_label' => null
        ]);
    }
}
