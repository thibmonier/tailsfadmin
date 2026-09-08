<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * US-016 — Tests fonctionnels du composant Upload (Dropzone).
 *
 * Vérifie :
 *   - La zone est rendue avec data-controller="tailsfadmin--dropzone"
 *   - L'attribut data-*-url-value pointe vers /demo/upload
 *   - L'endpoint stub POST /demo/upload répond 200 JSON
 *   - La section possède un ancre #section-upload (T-TECH-01)
 *   - La zone upload restreinte (acceptedFiles, maxFiles, maxFilesize)
 */
final class UploadTest extends WebTestCase
{
    /** La galerie /ui-kit rend la section upload. */
    public function testSectionUploadIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="section-upload"]');
    }

    /** La zone upload a bien le data-controller Stimulus. */
    public function testUploadZoneHasDataController(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-controller="tailsfadmin--dropzone"]');
    }

    /** L'attribut url-value pointe vers le stub endpoint. */
    public function testUploadZoneHasUrlValue(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropzone-url-value="/demo/upload"]'
        );
    }

    /** La zone upload basique est rendue (data-testid="upload-simple"). */
    public function testSimpleUploadZoneIsRendered(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="upload-simple"]');
        self::assertSelectorExists(
            '[data-testid="upload-simple"] [data-controller="tailsfadmin--dropzone"]'
        );
    }

    /** La zone upload avec restrictions est rendue. */
    public function testRestrictedUploadZoneIsRendered(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('[data-testid="upload-restricted"]');
        self::assertSelectorExists(
            '[data-testid="upload-restricted"] [data-tailsfadmin--dropzone-accepted-files-value="image/*"]'
        );
    }

    /** L'attribut max-files-value est transmis au contrôleur. */
    public function testMaxFilesValueIsTransmitted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropzone-max-files-value="3"]'
        );
    }

    /** L'attribut max-filesize-value est transmis au contrôleur. */
    public function testMaxFilesizeValueIsTransmitted(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists(
            '[data-tailsfadmin--dropzone-max-filesize-value="10"]'
        );
    }

    /** L'endpoint stub POST /demo/upload répond 200 avec du JSON. */
    public function testUploadStubEndpointReturns200(): void
    {
        $client = static::createClient();
        $client->request('POST', '/demo/upload');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');
    }

    /** Le contenu JSON du stub contient le champ "status". */
    public function testUploadStubEndpointReturnsJson(): void
    {
        $client = static::createClient();
        $client->request('POST', '/demo/upload');

        self::assertResponseIsSuccessful();
        $data = json_decode((string) $client->getResponse()->getContent(), true);
        self::assertIsArray($data);
        self::assertArrayHasKey('status', $data);
        self::assertSame('ok', $data['status']);
    }

    /** L'ancre #section-upload est présente (T-TECH-01). */
    public function testNavAnchorIsPresent(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ui-kit');

        self::assertResponseIsSuccessful();
        self::assertSelectorExists('a[href="#section-upload"]');
    }
}
