<?php

namespace PhpSolution\ApiUserBundle\TestCase;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @method Client getClient()
 */
trait UserTrait
{
    /**
     * @var string[]
     */
    protected static $tokens = [];

    /**
     * @param string $login
     * @param string $pass
     * @param bool   $forceLogin
     *
     * @return Client
     */
    protected function getAuthorizedClient(string $login, string $pass, bool $forceLogin = false): Client
    {
        $client = $this->getClient();
        $client->setServerParameter('HTTP_AUTHORIZATION', sprintf('Bearer %s', $this->getToken($login, $pass, $forceLogin)));

        return $client;
    }

    /**
     * @param string $login
     * @param string $pass
     * @param bool   $forceLogin
     *
     * @return string
     */
    private function getToken(string $login, string $pass, bool $forceLogin = false): string
    {
        $key = $login . $pass;
        if ($forceLogin || !array_key_exists($key, self::$tokens)) {
            $content = json_encode(['username' => $login, 'password' => $pass]);
            $client = $this->getClient();
            $client->request(Request::METHOD_POST, $this->getLoginUrl(), [], [], [], $content);
            $authData = json_decode($client->getResponse()->getContent(), true);
            if (array_key_exists('token', $authData)) {
                self::$tokens[$key] = $authData['token'];
            } else {
                throw new AuthenticationException($authData['error']);
            }
        }

        return self::$tokens[$key];
    }

    /**
     * @return string
     */
    abstract protected function getLoginUrl(): string;
}