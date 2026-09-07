# US-008 — Composant Alert

**EPIC :** EPIC-003-composants-ui · **Statut :** 🔴 To Do · **Points :** 3 · **Priorité :** Must · **Sprint :** backlog

## Carte (Card)
> En tant que **P-001 — développeur intégrateur**, je veux **un composant Twig Alert déclinable en quatre variantes (success, info, warning, error)**, afin de **afficher des messages contextuels cohérents dans toute l'interface d'administration sans dupliquer de HTML**.

## Conversation
Le composant Alert est un bloc de notification statique ou rétractable. Il couvre quatre niveaux sémantiques : success (vert), info (bleu), warning (orange), error (rouge). Les sources primaires se trouvent dans `Tools/sources/tailadmin-free-tailwind-dashboard-template-main/src/partials/alert/alert-*.html` (fichiers alert-01 à alert-04) ; la référence Blade Laravel est disponible dans `Tools/sources/tailadmin-laravel-main/resources/views/components/ui/alert.blade.php` et `alert-with-icon.blade.php`. Le composant est implémenté comme Twig Component (classe `AlertComponent`, template `components/alert.html.twig`). Il expose les props : `type` (enum success|info|warning|error), `title` (string optionnel), `message` (string), `dismissible` (bool, défaut false). La fermeture (variante dismissible) est gérée par un Stimulus controller léger (`alert_controller.js`) avec action `disconnect` → `hide` pour rester accessible sans JavaScript. Le composant respecte les rôles ARIA `role="alert"` / `role="status"` selon le niveau, inclut un icône SVG inline par variante, et supporte le dark mode via les classes Tailwind CSS v4 (`dark:`). Il est livré dans le bundle Symfony (namespaced `TailsAdmin`) ; la page de démo l'illustre dans `templates/demo/components/alerts.html.twig`.

## Confirmation — Critères d'acceptation (Gherkin)
### Scénario nominal
```gherkin
Feature: Affichage d'une alerte de succès
  Scenario: Rendu d'une alerte success avec titre et message
    Given le développeur intègre <twig:TailsAdmin:Alert type="success" title="Enregistrement réussi" message="Les données ont été sauvegardées." />
    When la page est rendue dans le navigateur
    Then un bloc portant role="status" est visible
    And le bloc affiche l'icône success et la couleur verte en mode clair
    And le titre "Enregistrement réussi" et le message "Les données ont été sauvegardées." sont présents dans le DOM
```
### Scénarios alternatifs
```gherkin
  Scenario: Alerte dismissible — fermeture au clic
    Given une alerte error avec dismissible="true" est affichée
    When l'utilisateur clique sur le bouton de fermeture
    Then l'alerte est masquée dans le DOM (aria-hidden="true" ou retrait du nœud)
    And aucune erreur JavaScript n'est levée

  Scenario: Alerte sans titre (message seul)
    Given le développeur intègre <twig:TailsAdmin:Alert type="warning" message="Quota presque atteint." />
    When la page est rendue
    Then le bloc est affiché sans élément titre
    And le message "Quota presque atteint." est visible avec la couleur orange

  Scenario: Dark mode appliqué
    Given la préférence système de l'utilisateur est dark ou la classe "dark" est sur <html>
    When une alerte info est affichée
    Then les couleurs de fond et de texte de l'alerte respectent les tokens dark Tailwind CSS v4
```
### Scénarios d'erreur
```gherkin
  Scenario: Prop type invalide
    Given le développeur passe type="critical" (valeur non supportée)
    When le composant est rendu en mode debug Symfony
    Then une exception LogicException est levée avec le message "Type d'alerte invalide : critical. Valeurs acceptées : success, info, warning, error."

  Scenario: Message vide
    Given le développeur intègre <twig:TailsAdmin:Alert type="info" message="" />
    When le composant est rendu en mode debug
    Then une exception LogicException est levée indiquant que le message ne peut pas être vide
```

## INVEST
- **Independent :** Ne dépend d'aucun autre composant UI en cours ; les tokens Tailwind et le layout sont des prérequis déjà stabilisés (US-002, US-004).
- **Negotiable :** L'animation de fermeture (slide vs fade) et la possibilité d'auto-dismiss avec timer sont négociables post-MVP.
- **Valuable :** Toute interface admin affiche des notifications ; un composant partagé garantit cohérence visuelle et accessibilité sans effort pour chaque page.
- **Estimable :** 4 variantes de template, 1 classe PHP, 1 Stimulus controller simple → estimé à 3 points (< 1 jour).
- **Small :** 3 points, périmètre clairement borné (pas de toast, pas de snackbar, pas d'animation complexe).
- **Testable :** Critères Gherkin couvrent les 4 variantes, le mode dismissible, le dark mode, et les erreurs de prop.

## Dépendances
- **Dépend de :** US-002 (Tailwind/tokens), US-004 (layout de base)
- **Bloque :** US-021

## Definition of Done
Voir `project-management/definition-of-done.md`.
