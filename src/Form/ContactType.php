<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'purify_html' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le champ ne peut pas être vide'
                        ])
                    ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'purify_html' => true,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Le champ ne peut pas être vide'
                        ]),
                        new Email([
                            'message' => "L'email {{ value }} n'est pas valide"
                        ])
                    ]
                ]
            )
            ->add('sujet', TextType::class, [
                'purify_html' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide'
                    ])
                ]
            ])
            ->add('message', TextareaType::class, ['purify_html' => true, 'constraints' => [
                new NotBlank([
                    'message' => 'Le champ ne peut pas être vide'
                ])
            ]])
            ->add('envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
