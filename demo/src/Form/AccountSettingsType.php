<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * US-038 — Formulaire de démonstration « Paramètres du compte ».
 *
 * Alimente le gabarit sectionné de la page /forms/layout : deux sections
 * (informations personnelles + préférences), chacune rendue en grille deux
 * colonnes responsive, avec des aides reliées via aria-describedby (option help).
 * Aucun widget custom : le form theme @Tailsfadmin/form/theme.html.twig s'applique.
 *
 * Les noms de champs sont en snake_case pour des identifiants HTML lisibles
 * (account_settings_first_name, account_settings_email, …).
 */
final class AccountSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Jean', 'autocomplete' => 'given-name'],
                'help' => 'Tel qu\'il apparaîtra sur votre profil.',
                'constraints' => [
                    new NotBlank(message: 'Le prénom est requis.'),
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Dupont', 'autocomplete' => 'family-name'],
                'constraints' => [
                    new NotBlank(message: 'Le nom est requis.'),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['placeholder' => 'jean@exemple.fr', 'autocomplete' => 'email'],
                'help' => 'Nous ne partagerons jamais votre adresse.',
                'constraints' => [
                    new NotBlank(message: 'L\'email est requis.'),
                    new Email(message: 'L\'adresse email n\'est pas valide.'),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'attr' => ['placeholder' => '+33 6 12 34 56 78', 'autocomplete' => 'tel'],
                'help' => 'Format international recommandé.',
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
            ->add('timezone', ChoiceType::class, [
                'label' => 'Fuseau horaire',
                'placeholder' => 'Choisir un fuseau…',
                'required' => false,
                'choices' => [
                    'Europe/Paris (UTC+1)' => 'Europe/Paris',
                    'Europe/London (UTC+0)' => 'Europe/London',
                    'America/New_York (UTC−5)' => 'America/New_York',
                    'Asia/Tokyo (UTC+9)' => 'Asia/Tokyo',
                ],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Biographie',
                'required' => false,
                'attr' => ['placeholder' => 'Quelques mots à propos de vous…', 'rows' => 4],
                'help' => 'Visible sur votre page de profil publique.',
            ])
            ->add('notifications', CheckboxType::class, [
                'label' => 'Recevoir les notifications par e-mail',
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
