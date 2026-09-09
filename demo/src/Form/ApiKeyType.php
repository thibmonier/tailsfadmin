<?php

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * US-039 — Formulaire de génération d'une clé d'API de démonstration.
 *
 * Champs : nom (obligatoire) et périmètre (lecture seule / lecture-écriture).
 * Aucune donnée réelle : la clé générée est factice, produite côté contrôleur.
 */
final class ApiKeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la clé',
                'attr' => ['placeholder' => 'Ex. Production, CI, Webhook Stripe…'],
                'help' => 'Un nom parlant pour retrouver l\'usage de la clé.',
                'constraints' => [
                    new NotBlank(message: 'Le nom de la clé est requis.'),
                    new Length(max: 64, maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('scope', ChoiceType::class, [
                'label' => 'Périmètre',
                'choices' => [
                    'Lecture seule' => 'read',
                    'Lecture et écriture' => 'read_write',
                ],
                'data' => 'read',
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
