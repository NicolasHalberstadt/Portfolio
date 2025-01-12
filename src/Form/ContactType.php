<?php

namespace App\Form;

use PixelOpen\CloudflareTurnstileBundle\Type\TurnstileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Jane', 'class' => 'form-label'],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['min' => 1])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Doe', 'class' => 'form-label'],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['min' => 1])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Où puis-je vous répondre ?', 'class' => 'form-label'],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['min' => 1]),
                    new Email(['message' => 'Ce champ doit contenir une adresse email valide.'])
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'attr' => ['placeholder' => 'Parlez-moi de votre projet web !', 'class' => 'form-label'],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ ne peut pas être vide.']),
                    new Length(['min' => 49, 'minMessage' => 'Votre message doit contenir 50 charactères minimum'])
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'E-commerce' => 'e-commerce',
                    'Vitrine' => 'vitrine',
                    'Projet personnalisé' => 'perso'
                ],
                'attr' => ['class' => 'form-label'],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Vous êtes interessé par un site :',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Vous devez selectionner 1 choix']),
                ]
            ])
            ->add('security', TurnstileType::class, ['attr' => ['data-action' => 'contact', 'data-theme' => 'dark'], 'label' => false])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
