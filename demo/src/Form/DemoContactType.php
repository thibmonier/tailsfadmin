<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * US-014 — Formulaire de contact de démonstration.
 *
 * Utilisé dans la galerie /ui-kit pour illustrer le form theme TailAdmin.
 * Champs : nom, email, message, rôle (select), newsletter (checkbox).
 */
final class DemoContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom complet',
                'attr' => ['placeholder' => 'Jean Dupont'],
                'constraints' => [
                    new NotBlank(message: 'Le nom est requis.'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['placeholder' => 'jean@exemple.fr'],
                'constraints' => [
                    new NotBlank(message: 'L\'email est requis.'),
                    new Email(message: 'L\'adresse email n\'est pas valide.'),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message…',
                    'rows' => 5,
                ],
                'constraints' => [
                    new NotBlank(message: 'Le message est requis.'),
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'placeholder' => 'Choisir un rôle…',
                'required' => false,
                'choices' => [
                    'Développeur' => 'dev',
                    'Designer' => 'designer',
                    'Chef de projet' => 'pm',
                    'Autre' => 'other',
                ],
            ])
            ->add('newsletter', CheckboxType::class, [
                'label' => 'Recevoir la newsletter',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
