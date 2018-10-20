<?php

namespace Rpodwika\TimesystemScrapper\Timesystem;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Rpodwika\TimesystemScrapper\Exception\CouldNotResolveHostException;

/**
 * Class Authenticator
 *
 * @package Rpodwika\TimesystemScrapper\Auth
 */
class TimesystemHttpClient
{
    private const STATUS_AUTH_OK = 'access granted ';

    /**
     * @var bool
     */
    private $isAuthenticated = false;

    /**
     * @var Client
     */
    private $client;

    /**
     * Authenticator constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param Credentials $credentials
     *
     * @return bool
     */
    public function authenticate(Credentials $credentials)
    {
        try {
            $this->client->request('GET', '/');

            $response = $this->client->request('POST', '/auth/login', [
                'form_params' => [
                    'username' => $credentials->getLogin(),
                    'password' => $credentials->getPassword(),
                ]
            ]);
        } catch (ConnectException $connectException) {
            throw new CouldNotResolveHostException("Could not resolve the host ", $connectException->getCode(),
                $connectException);
        }

        return $this->isAuthenticated = self::STATUS_AUTH_OK === strval($response->getBody());
    }

    /**
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    /**
     * Parse worktime table and return stamp in/outs and daysum
     *
     * @return array
     */
    public function getWorkTime()
    {
        $workTimeIndex = $this->client->request('GET', '/worktime/index?worktime=0');
        $html = $workTimeIndex->getBody();
        $regex = '#<tr class="(.*) confirmed" .*\s.*\s.*?<td class="worktime-table-body-in">(?<stampin>[\d\.\s:]+)<\/td>\s.*<td class="worktime-table-body-out">(?<stampout>[\d\.\s:]+).*\s.*<td class="worktime-table-body-daysum">(?<daysum>.*?)<\/td>\s+<td class="worktime-table-head-sumday">(?<monthsum>.*?)<\/td>#';

        if (false != preg_match_all($regex, $html, $match)) {
            return [
                'stampIn' => $match['stampin'],
                'stampOut' => $match['stampout'],
                'daySum' => $match['daysum'],
                'monthSum' => $match['monthsum'],
            ];
        }

        return [];
    }
}