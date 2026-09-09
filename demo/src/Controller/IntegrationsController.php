<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ApiKeyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * US-039 — Page « Integrations / API keys ».
 *
 * Liste des clés d'API (masquées), révélables, copiables en un clic (contrôleur
 * Stimulus tailsfadmin--clipboard), avec génération et révocation via tsf:Ui:Modal.
 * Onglets via tsf:Ui:Tabs.
 *
 * Données STRICTEMENT factices, stockées en session (aucune base de données,
 * aucun secret réel). La clé générée en clair n'est affichée qu'une seule fois
 * (flash, pattern Post/Redirect/Get).
 *
 * @phpstan-type ApiKeyRecord array{id:string, name:string, masked:string, plain:string, scope:string, created:string, lastUsed:string, status:string}
 */
final class IntegrationsController extends AbstractController
{
    private const SESSION_KEY = 'integrations_api_keys';

    #[Route('/integrations', name: 'integrations', methods: ['GET', 'POST'])]
    public function index(Request $request, FormFactoryInterface $formFactory): Response
    {
        $session = $request->getSession();
        /** @var list<ApiKeyRecord> $keys */
        $keys = $session->get(self::SESSION_KEY) ?? $this->seedKeys();

        $status = Response::HTTP_OK;

        $generateForm = $formFactory->createNamed('api_key', ApiKeyType::class, null, [
            'action' => $this->generateUrl('integrations'),
            'method' => 'POST',
        ]);
        $generateForm->handleRequest($request);

        if ($generateForm->isSubmitted()) {
            if ($generateForm->isValid()) {
                /** @var array{name:string, scope:string} $data */
                $data = $generateForm->getData();
                $created = $this->createKey($data['name'], $data['scope']);

                array_unshift($keys, $created['record']);
                $session->set(self::SESSION_KEY, $keys);

                // Affichée une seule fois via flash (Post/Redirect/Get).
                $this->addFlash('generated_key', $created['plain']);
                $this->addFlash('generated_name', $created['record']['name']);

                return $this->redirectToRoute('integrations');
            }

            // Soumission invalide : on réaffiche la page avec 422 (l'erreur reste
            // visible dans le formulaire du modal et dans la bannière d'erreur).
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        } else {
            $session->set(self::SESSION_KEY, $keys);
        }

        $revokeForms = [];
        foreach ($keys as $key) {
            $revokeForms[$key['id']] = $this->revokeForm($formFactory, $key['id'], true)->createView();
        }

        return $this->render('integrations/index.html.twig', [
            'keys' => $keys,
            'generateForm' => $generateForm,
            'revokeForms' => $revokeForms,
        ], new Response(status: $status));
    }

    #[Route('/integrations/keys/{id}/revoke', name: 'integrations_revoke', methods: ['POST'], requirements: ['id' => '[a-z0-9_]+'])]
    public function revoke(string $id, Request $request, FormFactoryInterface $formFactory): Response
    {
        $form = $this->revokeForm($formFactory, $id, false);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
            /** @var list<ApiKeyRecord> $keys */
            $keys = $session->get(self::SESSION_KEY, []);

            foreach ($keys as $index => $key) {
                if ($key['id'] === $id) {
                    $keys[$index]['status'] = 'revoked';
                    $this->addFlash('success', 'La clé « ' . $key['name'] . ' » a été révoquée.');
                    break;
                }
            }

            $session->set(self::SESSION_KEY, $keys);
        }

        return $this->redirectToRoute('integrations');
    }

    /**
     * Formulaire de révocation d'une clé. Un champ caché garantit que la
     * soumission est détectée (un formulaire sans champ ne l'est pas avec la
     * CSRF stateless, qui ne rend pas de champ _token).
     */
    private function revokeForm(FormFactoryInterface $formFactory, string $id, bool $withAction): FormInterface
    {
        $options = ['method' => 'POST'];
        if ($withAction) {
            $options['action'] = $this->generateUrl('integrations_revoke', ['id' => $id]);
        }

        return $formFactory
            ->createNamedBuilder('revoke_' . $id, FormType::class, null, $options)
            ->add('confirm', HiddenType::class, ['data' => '1'])
            ->getForm();
    }

    /**
     * @return list<ApiKeyRecord>
     */
    private function seedKeys(): array
    {
        return [
            $this->makeRecord('key_prod', 'Production', 'sk_live_5f8Ka92JdQ7x', 'read_write', '12/03/2026', 'Il y a 2 heures', 'active'),
            $this->makeRecord('key_ci', 'Intégration continue', 'sk_live_2bH7pL4mNz19', 'read', '30/05/2026', 'Il y a 5 jours', 'active'),
            $this->makeRecord('key_legacy', 'Ancien script', 'sk_test_9xQ0rWv3Tc44', 'read', '02/11/2025', 'Il y a 3 mois', 'revoked'),
        ];
    }

    /**
     * @return array{plain:string, record:ApiKeyRecord}
     */
    private function createKey(string $name, string $scope): array
    {
        $plain = 'sk_live_' . bin2hex(random_bytes(8));
        $id = 'key_' . bin2hex(random_bytes(4));

        return [
            'plain' => $plain,
            'record' => $this->makeRecord($id, $name, $plain, $scope, date('d/m/Y'), 'Jamais', 'active'),
        ];
    }

    /**
     * @return ApiKeyRecord
     */
    private function makeRecord(string $id, string $name, string $plain, string $scope, string $created, string $lastUsed, string $status): array
    {
        return [
            'id' => $id,
            'name' => $name,
            'masked' => substr($plain, 0, 8) . '••••' . substr($plain, -4),
            'plain' => $plain,
            'scope' => $scope,
            'created' => $created,
            'lastUsed' => $lastUsed,
            'status' => $status,
        ];
    }
}
