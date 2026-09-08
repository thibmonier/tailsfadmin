<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test fonctionnel — US-022 : Page profil + modales d'édition.
 *
 * Vérifie la carte profil, les blocs infos/adresse, et les deux modales
 * d'édition (tsf:Ui:Modal) qui embarquent des composants de formulaire.
 * La revue visuelle (dont une modale ouverte) reste le filet principal.
 */
final class ProfileTest extends WebTestCase
{
    public function testProfileReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(200);
    }

    public function testProfileShowsUserCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile');

        self::assertSelectorTextContains('body', 'Thomas Martin');
        self::assertSelectorTextContains('body', 'Responsable produit');
    }

    public function testProfileShowsInfoAndAddressSections(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile');

        self::assertSelectorTextContains('body', 'Informations personnelles');
        self::assertSelectorTextContains('body', 'Adresse');
    }

    public function testProfileHasTwoEditModals(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        // Deux modales d'édition, chacune câblée au contrôleur Stimulus modal.
        self::assertSame(2, $crawler->filter('[data-controller="tailsfadmin--modal"]')->count());
        self::assertGreaterThanOrEqual(2, $crawler->filter('[role="dialog"]')->count());
    }

    public function testEditModalsContainForms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        // Formulaires + champs de composants form présents dans les modales.
        self::assertGreaterThanOrEqual(2, $crawler->filter('[data-controller="tailsfadmin--modal"] form')->count());
        self::assertGreaterThanOrEqual(1, $crawler->filter('input[name="first_name"]')->count());
        self::assertGreaterThanOrEqual(1, $crawler->filter('input[name="country"]')->count());
    }

    public function testEditButtonsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/profile');

        self::assertSelectorTextContains('body', 'Modifier');
    }

    public function testProfilePostRedirects(): void
    {
        $client = static::createClient();
        $client->request('POST', '/profile', ['first_name' => 'Thomas']);

        // PRG : pas de persistance (démo), on redirige vers la page profil.
        self::assertResponseRedirects('/profile');
    }
}
