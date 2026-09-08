<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Bascule de langue de l'interface — US-024.
 *
 * Persiste la locale choisie en session (lue ensuite par LocaleSubscriber du
 * bundle à chaque requête), puis redirige vers la page d'origine (PRG).
 * La locale est validée contre la whitelist du bundle (%tailsfadmin.locales%) ;
 * toute valeur hors liste est ignorée.
 *
 * @param list<string> $locales
 */
final class LocaleController extends AbstractController
{
    public function __construct(
        #[Autowire('%tailsfadmin.locales%')]
        private readonly array $locales,
    ) {
    }

    #[Route('/locale/{locale}', name: 'locale_switch', methods: ['GET'])]
    public function switch(string $locale, Request $request): RedirectResponse
    {
        if (\in_array($locale, $this->locales, true)) {
            $request->getSession()->set('_locale', $locale);
        }

        // Redirection vers la page d'origine, uniquement si same-origin (anti open-redirect).
        $referer = $request->headers->get('referer');

        if (\is_string($referer) && str_starts_with($referer, $request->getSchemeAndHttpHost())) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('home');
    }
}
