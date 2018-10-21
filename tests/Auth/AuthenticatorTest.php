<?php

namespace Rpodwika\Test\Auth;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Rpodwika\TimesystemScrapper\Scrapper\Timesystem;
use Rpodwika\TimesystemScrapper\Timesystem\{
    TimesystemHttpClient, Credentials
};

/**
 * Class AuthenticatorTest
 *
 * @package Rpodwika\Test\Auth
 */
class AuthenticatorTest extends TestCase
{
    public function testAuthReturnsFalseOnWrongCredentials()
    {
        $client = new Client([
            'base_uri' => 'https://timesystem.sportradar.ag',
            'timeout' => 2.0,
            'cookies' => true,
        ]);

        $timesystemHttpClient = new TimesystemHttpClient($client);
        $timesystem = new Timesystem($timesystemHttpClient);

        $this->assertFalse($timesystemHttpClient->authenticate(new Credentials('b', 'a')));
    }
}