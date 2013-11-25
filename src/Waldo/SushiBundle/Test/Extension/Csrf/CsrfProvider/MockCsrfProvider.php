<?php

namespace Waldo\SushiBundle\Test\Extension\Csrf\CsrfProvider;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;

/**
 * MockCsrfProvider
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 * code from https://github.com/benja-M-1/sflive-paris-2013
 */
class MockCsrfProvider implements CsrfProviderInterface
{
    /**
     * Generates a CSRF token for a page of your application.
     *
     * @param string $intention Some value that identifies the action intention
     *                          (i.e. "authenticate"). Doesn't have to be a secret value.
     */
    public function generateCsrfToken($intention)
    {
        return $intention;
    }

    /**
     * Validates a CSRF token.
     *
     * @param string $intention The intention used when generating the CSRF token
     * @param string $token     The token supplied by the browser
     *
     * @return Boolean Whether the token supplied by the browser is correct
     */
    public function isCsrfTokenValid($intention, $token)
    {
        return $intention == $token;
    }

}
