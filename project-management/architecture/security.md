# Sécurité — tailsfadmin

> Projet de type thème/bundle : surface d'attaque réduite (pas de données métier, pas d'API applicative). La sécurité porte surtout sur la **chaîne d'approvisionnement front**, les **en-têtes**, la **gestion d'erreurs** et le fait de **ne pas livrer d'anti-patterns** aux consommateurs. Référence : `.claude/rules/11-security.md` (OWASP Top 10:2025).

## Périmètre & modèle de menace

| Élément | Sensibilité | Note |
|---------|-------------|------|
| Pages d'authentification (signin/signup) | **UI de démo uniquement** | Aucune logique d'auth réelle (US-023) — ne pas les présenter comme prêtes pour la prod |
| Librairies JS tierces vendorées | Moyenne | Chaîne d'appro. front → voir supply chain |
| Contenu statique / templates | Faible | Pas de données utilisateur |
| Binaire Tailwind standalone | Moyenne | Téléchargé au build → intégrité |

## OWASP Top 10:2025 — points applicables

### A03 Injection / XSS (le plus pertinent)
- Twig **échappe par défaut** : ne jamais utiliser `|raw` sur des entrées ; les icônes SVG inline proviennent de constantes du bundle, pas d'entrées utilisateur.
- Les contrôleurs Stimulus ne doivent pas injecter d'HTML non fiable (`innerHTML`) — préférer `textContent` / API DOM.

### A05 Security Misconfiguration — En-têtes de sécurité
La démo configure des en-têtes compatibles avec un pipeline AssetMapper (assets **vendorés**, pas de CDN runtime) :
- `Content-Security-Policy` : `default-src 'self'` ; `script-src 'self'` ; `style-src 'self'` — possible **grâce au vendoring** (aucun `unsafe-inline` requis pour les libs). Attention aux styles inline générés par certaines libs (ApexCharts) → tester la CSP réelle.
- `X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`, `Referrer-Policy: strict-origin-when-cross-origin`.
- `Strict-Transport-Security` (prod/HTTPS via FrankenPHP).

### A06 Software Supply Chain (nouveau 2025) — libs JS
- **Versions épinglées** : `importmap.php` fige les versions (pas de `latest`) ; `binary_version` Tailwind épinglé (ADR-001).
- **Vendoring local** (pas de CDN runtime) → pas d'exécution de code tiers non figé.
- Scan CVE des dépendances (Composer + JS) en CI (US-026).
- Intégrité des assets vendorés (AssetMapper gère des digests de contenu).

### A07 Mishandling of Exceptional Conditions
- La page **404** est branchée sur le mécanisme d'erreur Symfony ; en **prod, pas de stack trace** exposée (US-023 le couvre explicitement).

## Ce que le bundle ne doit PAS faire (anti-patterns à ne pas propager)
- Pas de secrets/API keys dans le code ou les templates.
- Pas d'appels réseau sortants cachés depuis les contrôleurs Stimulus.
- Pas d'HTML non échappé rendu depuis des props de composants.
- Les pages auth de démo ne doivent pas suggérer un stockage de mot de passe (aucune persistance).

## Checklist (extrait, alignée sur la règle 11)
- [ ] CSP stricte testée sur toutes les pages (attention styles inline des libs)
- [ ] En-têtes de sécurité posés côté démo (FrankenPHP/Symfony)
- [ ] Versions front épinglées (importmap, binaire Tailwind)
- [ ] Scan CVE Composer + JS en CI
- [ ] Aucune donnée sensible / secret dans le dépôt
- [ ] Prod : pas de stack trace, `APP_ENV=prod`, debug off
- [ ] Twig : aucun `|raw` sur entrée non fiable

## Références
- `.claude/rules/11-security.md` (OWASP Top 10:2025, en-têtes 2026, supply chain)
- [OWASP Top 10:2025](https://owasp.org/Top10/2025/)
