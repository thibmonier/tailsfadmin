<?php

declare(strict_types=1);

namespace Tailsfadmin\Menu;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Service MenuBuilder — construit la structure de menu depuis la configuration.
 *
 * Reçoit la liste brute des groupes (array depuis tailsfadmin.yaml) et produit
 * des objets MenuGroup/MenuItem typés, prêts à être consommés par Twig.
 *
 * Filtrage par permission (optionnel) : si un item déclare une clé `permission`
 * et qu'un AuthorizationCheckerInterface est disponible, l'item n'est retourné
 * que si l'utilisateur courant y est autorisé (is_granted). Sans checker
 * (sécurité non installée), tout est visible — rétro-compatible.
 *
 * Configuration attendue (format YAML) :
 *
 *   tailsfadmin:
 *     menu:
 *       - group: Menu
 *         items:
 *           - label: Dashboard
 *             path: /
 *             icon: dashboard
 *             permission: ~        # optionnel (ex. 'ROLE_ADMIN', 'view:reports')
 *             children: []
 */
final class MenuBuilder
{
    /** @var list<array<string, mixed>> */
    private array $config;

    /** @param list<array<string, mixed>> $config */
    public function __construct(
        array $config,
        private readonly ?AuthorizationCheckerInterface $authChecker = null,
    ) {
        $this->config = $config;
    }

    /**
     * Retourne les groupes de menu construits depuis la configuration.
     *
     * Les items non autorisés sont retirés ; un groupe dont tous les items
     * sont filtrés n'est pas retourné (pas d'en-tête de groupe vide).
     *
     * @return MenuGroup[]
     */
    public function getGroups(): array
    {
        $groups = [];

        foreach ($this->config as $groupConfig) {
            $label = $this->str($groupConfig['group'] ?? '');

            /** @var list<array<string, mixed>> $rawItems */
            $rawItems = isset($groupConfig['items']) && is_array($groupConfig['items'])
                ? array_values($groupConfig['items'])
                : [];

            $items = $this->buildItems($rawItems);

            if ([] === $items) {
                continue;
            }

            $groups[] = new MenuGroup(
                label: $label,
                items: $items,
            );
        }

        return $groups;
    }

    /**
     * @param  list<array<string, mixed>> $itemsConfig
     * @return MenuItem[]
     */
    private function buildItems(array $itemsConfig): array
    {
        $items = [];

        foreach ($itemsConfig as $itemConfig) {
            $permission = $this->nullableStr($itemConfig['permission'] ?? null);

            if (!$this->isGranted($permission)) {
                continue;
            }

            /** @var list<array<string, mixed>> $rawChildren */
            $rawChildren = isset($itemConfig['children']) && is_array($itemConfig['children'])
                ? array_values($itemConfig['children'])
                : [];

            $children = $this->buildItems($rawChildren);
            $path = $this->str($itemConfig['path'] ?? '#');

            // Un parent purement conteneur (path '#'/'') dont tous les enfants
            // ont été filtrés ne mène nulle part : on le retire.
            if ([] !== $rawChildren && [] === $children && ('#' === $path || '' === $path)) {
                continue;
            }

            $items[] = new MenuItem(
                label:      $this->str($itemConfig['label'] ?? ''),
                path:       $path,
                icon:       $this->str($itemConfig['icon'] ?? ''),
                children:   $children,
                permission: $permission,
            );
        }

        return $items;
    }

    /**
     * Détermine si l'utilisateur courant peut voir un item.
     *
     * Sans permission déclarée, ou sans checker (sécurité non installée),
     * l'item est visible. Hors contexte d'authentification, il est masqué.
     */
    private function isGranted(?string $permission): bool
    {
        if (null === $permission || '' === $permission) {
            return true;
        }

        if (null === $this->authChecker) {
            return true;
        }

        try {
            return $this->authChecker->isGranted($permission);
        } catch (AuthenticationCredentialsNotFoundException) {
            return false;
        }
    }

    /**
     * Convertit une valeur scalaire en chaîne de caractères.
     * Retourne une chaîne vide pour les valeurs non scalaires.
     */
    private function str(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }

    /**
     * Convertit une valeur scalaire en chaîne, ou null si vide/non scalaire.
     */
    private function nullableStr(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $string = (string) $value;

        return '' === $string ? null : $string;
    }
}
