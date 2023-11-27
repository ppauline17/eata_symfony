<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DocumentType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $is_creation = $options['is_creation'];
        $chooseCategory = $options['chooseCategory'];

        $builder
            ->add('label')
            ->add('documentSource', FileType::class, [
                'data_class' => null,
                'required' => $is_creation,
                'empty_data' => $is_creation ? true : false,
                'constraints' => $is_creation ? [
                    new File([
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                    ]),
                ] : [],
            ])
            ->add('old_document', HiddenType::class);
        if ($chooseCategory){
            $builder
                ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'label',
                    'multiple' => true,
                    'expanded' => true,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->andWhere('c.label IN (:label)')
                            ->setParameter('label', [
                                'périscolaire', 
                                'mercredi', 
                                'loisirs'
                            ]);
                    },
                ])
            ;
        }else{
            $associationCategory = $this->entityManager->getRepository(Category::class)->findOneBy(['label' => 'association']);
            $builder
                ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'choice_label' => 'label',
                    'multiple' => true,
                    'expanded' => true,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->andWhere('c.label IN (:label)')
                            ->setParameter('label', [
                                'association',
                            ]);
                    },
                    'data' => [$associationCategory],
                    'attr' => [
                        'class' => 'd-none',
                    ]
                ])
            ;
        }
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'is_creation' => false,
            'chooseCategory' => false
        ]);
    }
}
