<?php

declare(strict_types=1);

namespace Tailsfadmin\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension Twig tailsfadmin.
 *
 * Fonctions disponibles dans les templates :
 *   - is_active(path)  : retourne true si le chemin courant correspond au path donné
 *   - tsf_icon(name)   : retourne le SVG inline de l'icône demandée
 *   - tsf_dir()        : retourne 'rtl' ou 'ltr' selon la locale courante (US-024)
 */
final class TailsfadminExtension extends AbstractExtension
{
    /** Métadonnées d'affichage par locale (nom natif + drapeau). */
    private const LOCALE_META = [
        'fr' => ['native' => 'Français', 'flag' => '🇫🇷'],
        'en' => ['native' => 'English', 'flag' => '🇬🇧'],
        'ar' => ['native' => 'العربية', 'flag' => '🇸🇦'],
        'es' => ['native' => 'Español', 'flag' => '🇪🇸'],
        'de' => ['native' => 'Deutsch', 'flag' => '🇩🇪'],
    ];

    /**
     * @param list<string> $locales    Whitelist des locales supportées
     * @param list<string> $rtlLocales Locales rendues de droite à gauche
     */
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly array $rtlLocales = ['ar'],
        private readonly array $locales = ['fr', 'en'],
    ) {
    }

    /** @return TwigFunction[] */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_active', $this->isActive(...)),
            new TwigFunction('tsf_icon', $this->tsfIcon(...), ['is_safe' => ['html']]),
            new TwigFunction('tsf_dir', $this->tsfDir(...)),
            new TwigFunction('tsf_locales', $this->tsfLocales(...)),
        ];
    }

    /**
     * Liste des locales supportées avec métadonnées d'affichage (US-024).
     *
     * @return list<array{code: string, native: string, flag: string, dir: string, current: bool}>
     */
    public function tsfLocales(): array
    {
        $current = null !== ($request = $this->requestStack->getCurrentRequest())
            ? $request->getLocale()
            : '';

        $result = [];
        foreach ($this->locales as $code) {
            $meta = self::LOCALE_META[$code] ?? ['native' => strtoupper($code), 'flag' => '🏳️'];
            $result[] = [
                'code' => $code,
                'native' => $meta['native'],
                'flag' => $meta['flag'],
                'dir' => \in_array($code, $this->rtlLocales, true) ? 'rtl' : 'ltr',
                'current' => $code === $current,
            ];
        }

        return $result;
    }

    /**
     * Retourne la direction d'écriture ('rtl' ou 'ltr') de la locale courante (US-024).
     */
    public function tsfDir(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $locale = null !== $request ? $request->getLocale() : '';

        return \in_array($locale, $this->rtlLocales, true) ? 'rtl' : 'ltr';
    }

    /**
     * Retourne true si le chemin de la requête courante correspond à $path.
     *
     * Correspondance exacte sur le pathinfo (sans query string).
     */
    public function isActive(string $path): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return false;
        }

        return $request->getPathInfo() === $path;
    }

    /**
     * Retourne un SVG inline pour l'icône demandée.
     *
     * Les icônes sont portées depuis TailAdmin (source : MenuHelper.php Laravel).
     */
    public function tsfIcon(string $name): string
    {
        return match ($name) {
            'dashboard' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"/>
</svg>
SVG,
            'calendar' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M6 2.75C6 2.33579 6.33579 2 6.75 2C7.16421 2 7.5 2.33579 7.5 2.75V4H16.5V2.75C16.5 2.33579 16.8358 2 17.25 2C17.6642 2 18 2.33579 18 2.75V4H19C20.6569 4 22 5.34315 22 7V19C22 20.6569 20.6569 22 19 22H5C3.34315 22 2 20.6569 2 19V7C2 5.34315 3.34315 4 5 4H6V2.75ZM5 5.5C4.17157 5.5 3.5 6.17157 3.5 7V8.5H20.5V7C20.5 6.17157 19.8284 5.5 19 5.5H5ZM20.5 10H3.5V19C3.5 19.8284 4.17157 20.5 5 20.5H19C19.8284 20.5 20.5 19.8284 20.5 19V10Z" fill="currentColor"/>
</svg>
SVG,
            'user-profile' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2ZM8.5 7C8.5 5.067 10.067 3.5 12 3.5C13.933 3.5 15.5 5.067 15.5 7C15.5 8.933 13.933 10.5 12 10.5C10.067 10.5 8.5 8.933 8.5 7ZM12 14C7.85786 14 4.5 17.1339 4.5 21C4.5 21.4142 4.83579 21.75 5.25 21.75C5.66421 21.75 6 21.4142 6 21C6 17.9624 8.68629 15.5 12 15.5C15.3137 15.5 18 17.9624 18 21C18 21.4142 18.3358 21.75 18.75 21.75C19.1642 21.75 19.5 21.4142 19.5 21C19.5 17.1339 16.1421 14 12 14Z" fill="currentColor"/>
</svg>
SVG,
            'forms' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 3C3.25 2.58579 3.58579 2.25 4 2.25H20C20.4142 2.25 20.75 2.58579 20.75 3V21C20.75 21.4142 20.4142 21.75 20 21.75H4C3.58579 21.75 3.25 21.4142 3.25 21V3ZM4.75 3.75V20.25H19.25V3.75H4.75ZM7 7.25C7 6.83579 7.33579 6.5 7.75 6.5H16.25C16.6642 6.5 17 6.83579 17 7.25C17 7.66421 16.6642 8 16.25 8H7.75C7.33579 8 7 7.66421 7 7.25ZM7.75 10.5C7.33579 10.5 7 10.8358 7 11.25C7 11.6642 7.33579 12 7.75 12H16.25C16.6642 12 17 11.6642 17 11.25C17 10.8358 16.6642 10.5 16.25 10.5H7.75ZM7 15.25C7 14.8358 7.33579 14.5 7.75 14.5H12.25C12.6642 14.5 13 14.8358 13 15.25C13 15.6642 12.6642 16 12.25 16H7.75C7.33579 16 7 15.6642 7 15.25Z" fill="currentColor"/>
</svg>
SVG,
            'tables' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 3C3.25 2.58579 3.58579 2.25 4 2.25H20C20.4142 2.25 20.75 2.58579 20.75 3V21C20.75 21.4142 20.4142 21.75 20 21.75H4C3.58579 21.75 3.25 21.4142 3.25 21V3ZM4.75 3.75V8.25H11.25V3.75H4.75ZM12.75 3.75V8.25H19.25V3.75H12.75ZM19.25 9.75H12.75V14.25H19.25V9.75ZM12.75 15.75V20.25H19.25V15.75H12.75ZM11.25 20.25V15.75H4.75V20.25H11.25ZM4.75 14.25H11.25V9.75H4.75V14.25Z" fill="currentColor"/>
</svg>
SVG,
            'pages' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M4 3.25C3.58579 3.25 3.25 3.58579 3.25 4V20C3.25 20.4142 3.58579 20.75 4 20.75H20C20.4142 20.75 20.75 20.4142 20.75 20V8C20.75 7.80109 20.671 7.61032 20.5303 7.46967L13.5303 0.469668C13.3897 0.329018 13.1989 0.25 13 0.25H4ZM4.75 4.75V19.25H19.25V8.75H13C12.5858 8.75 12.25 8.41421 12.25 8V4.75H4.75ZM13.75 2.81066L17.6893 6.75H13.75V2.81066Z" fill="currentColor"/>
</svg>
SVG,
            'charts' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 3C3.25 2.58579 3.58579 2.25 4 2.25H20C20.4142 2.25 20.75 2.58579 20.75 3V21C20.75 21.4142 20.4142 21.75 20 21.75H4C3.58579 21.75 3.25 21.4142 3.25 21V3ZM4.75 3.75V20.25H19.25V3.75H4.75ZM8 14.25C8.41421 14.25 8.75 14.5858 8.75 15V18C8.75 18.4142 8.41421 18.75 8 18.75C7.58579 18.75 7.25 18.4142 7.25 18V15C7.25 14.5858 7.58579 14.25 8 14.25ZM12 10.25C12.4142 10.25 12.75 10.5858 12.75 11V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V11C11.25 10.5858 11.5858 10.25 12 10.25ZM16 6.25C16.4142 6.25 16.75 6.58579 16.75 7V18C16.75 18.4142 16.4142 18.75 16 18.75C15.5858 18.75 15.25 18.4142 15.25 18V7C15.25 6.58579 15.5858 6.25 16 6.25Z" fill="currentColor"/>
</svg>
SVG,
            'ui-elements' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 6C2.25 4.20508 3.70508 2.75 5.5 2.75H18.5C20.2949 2.75 21.75 4.20508 21.75 6V18C21.75 19.7949 20.2949 21.25 18.5 21.25H5.5C3.70508 21.25 2.25 19.7949 2.25 18V6ZM5.5 4.25C4.5335 4.25 3.75 5.0335 3.75 6V18C3.75 18.9665 4.5335 19.75 5.5 19.75H18.5C19.4665 19.75 20.25 18.9665 20.25 18V6C20.25 5.0335 19.4665 4.25 18.5 4.25H5.5ZM8 8.25C7.58579 8.25 7.25 8.58579 7.25 9C7.25 9.41421 7.58579 9.75 8 9.75H16C16.4142 9.75 16.75 9.41421 16.75 9C16.75 8.58579 16.4142 8.25 16 8.25H8ZM7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12ZM8 14.25C7.58579 14.25 7.25 14.5858 7.25 15C7.25 15.4142 7.58579 15.75 8 15.75H12C12.4142 15.75 12.75 15.4142 12.75 15C12.75 14.5858 12.4142 14.25 12 14.25H8Z" fill="currentColor"/>
</svg>
SVG,
            'authentication' => <<<'SVG'
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C9.37665 1.25 7.25 3.37665 7.25 6V7.25H6C4.48122 7.25 3.25 8.48122 3.25 10V19C3.25 20.5188 4.48122 21.75 6 21.75H18C19.5188 21.75 20.75 20.5188 20.75 19V10C20.75 8.48122 19.5188 7.25 18 7.25H16.75V6C16.75 3.37665 14.6234 1.25 12 1.25ZM15.25 7.25V6C15.25 4.20507 13.7949 2.75 12 2.75C10.2051 2.75 8.75 4.20507 8.75 6V7.25H15.25ZM6 8.75C5.30964 8.75 4.75 9.30964 4.75 10V19C4.75 19.6904 5.30964 20.25 6 20.25H18C18.6904 20.25 19.25 19.6904 19.25 19V10C19.25 9.30964 18.6904 8.75 18 8.75H6ZM12 12.25C12.4142 12.25 12.75 12.5858 12.75 13V16C12.75 16.4142 12.4142 16.75 12 16.75C11.5858 16.75 11.25 16.4142 11.25 16V13C11.25 12.5858 11.5858 12.25 12 12.25Z" fill="currentColor"/>
</svg>
SVG,
            default => sprintf(
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5"/><text x="12" y="16" text-anchor="middle" font-size="10" fill="currentColor">%s</text></svg>',
                htmlspecialchars(substr($name, 0, 2), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            ),
        };
    }
}
