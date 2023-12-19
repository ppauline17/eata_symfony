<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    private $emailEata;
    private $emailAsso;

    public function __construct($emailEata, $emailAsso)
    {
        $this->emailEata = $emailEata;
        $this->emailAsso = $emailAsso;
    }

    public function getEmailEata()
    {
        return $this->emailEata;
    }

    public function getEmailAsso()
    {
        return $this->emailAsso;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('to', ChoiceType::class, [
                'label' => 'Qui souhaitez-vous contacter ?',
                'attr' => [
                    'class' => 'contact-email-choice'
                ],
                'choices' => [
                    'La directrice EATA' => $this->getEmailEata(),
                    'Le bureau associatif' => $this->getEmailAsso(),
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir un destinataire',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre adresse email',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => [
                    'style' => 'height: 200px'
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre message',
                    ]),
                ],
            ])
            ->add('rgpd', CheckboxType::class, [
                'label' => 'En validant ce formulaire, j\'accepte que mes données soient utilisées dans le cadre de la demande de contact.',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez accepter les conditions d\'utilisation',
                    ]),
                ],
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
