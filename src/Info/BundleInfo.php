<?php

declare(strict_types=1);

namespace Tailsfadmin\Info;

/**
 * Service d'information sur le bundle tailsfadmin.
 *
 * Exposé sous l'id `tailsfadmin.bundle_info` dans le conteneur DI
 * (cf. config/services.yaml) afin de satisfaire l'exigence DoD :
 * `debug:container tailsfadmin` liste ≥ 1 service.
 */
final class BundleInfo
{
    /**
     * Retourne le nom canonique du bundle.
     */
    public function name(): string
    {
        return 'tailsfadmin';
    }

    /**
     * Retourne la version courante du bundle.
     */
    public function version(): string
    {
        return '0.1.0-dev';
    }
}
