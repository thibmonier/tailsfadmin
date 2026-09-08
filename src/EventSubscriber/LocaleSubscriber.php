<?php

declare(strict_types=1);

namespace Tailsfadmin\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Résout la locale de la requête depuis la session (US-024, ADR-005).
 *
 * Priorité 20 : s'exécute AVANT le LocaleListener natif de Symfony (16) afin
 * d'imposer la locale choisie par l'utilisateur et persistée en session par
 * LocaleController. La locale est validée contre une whitelist ; toute valeur
 * hors liste retombe sur la locale par défaut (jamais de locale arbitraire).
 */
final readonly class LocaleSubscriber implements EventSubscriberInterface
{
    /**
     * @param list<string> $locales Whitelist des locales supportées
     */
    public function __construct(
        private string $defaultLocale,
        private array $locales,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        $locale = $request->getSession()->get('_locale', $this->defaultLocale);

        if (!\is_string($locale) || !\in_array($locale, $this->locales, true)) {
            $locale = $this->defaultLocale;
        }

        $request->setLocale($locale);
    }

    /**
     * @return array<string, array{0: string, 1: int}>
     */
    public static function getSubscribedEvents(): array
    {
        // Priorité 20 > LocaleListener natif (16) pour imposer la locale de session.
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 20],
        ];
    }
}
