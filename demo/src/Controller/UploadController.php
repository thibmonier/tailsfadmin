<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * US-016 — Stub endpoint upload pour la démo Dropzone.
 *
 * Répond 200 JSON à toute requête POST /demo/upload,
 * ce qui permet à Dropzone de terminer le cycle upload
 * sans erreur dans la galerie /ui-kit.
 */
final class UploadController extends AbstractController
{
    #[Route('/demo/upload', name: 'demo_upload', methods: ['POST'])]
    public function upload(): JsonResponse
    {
        return $this->json(['status' => 'ok']);
    }
}
