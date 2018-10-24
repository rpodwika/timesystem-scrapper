<?php

namespace Rpodwika\TimesystemScrapper\Timesystem;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Rpodwika\TimesystemScrapper\Exception\{
    AuthenticationRequiredException, CouldNotResolveHostException
};

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
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticate(Credentials $credentials)
    {
        try {
            $this->client->request('GET', '/');

            $response = $this->client->request(
                'POST',
                '/auth/login',
                [
                    'form_params' => [
                        'username' => $credentials->getLogin(),
                        'password' => $credentials->getPassword(),
                    ],
                ]
            );
        } catch (ConnectException $connectException) {
            throw new CouldNotResolveHostException(
                "Could not resolve the host ", $connectException->getCode(),
                $connectException
            );
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
     * Get user name and id
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserInformation(): array
    {
        $this->checkAuthentication();

        $dashboard = $this->client->request('GET', '/dashboard');

        preg_match("#<p>Welcome, (?<username>.*?)<\/p>#", (string)$dashboard->getBody(), $match);
        preg_match("#socket.emit\('set_uid', (?<uid>\d+?)\);#", $dashboard->getBody(), $matchUid);
        preg_match('#<div class=".*?"data-url.*status\/(?<status>\d)#', $dashboard->getBody(), $matchStamp);

        $stampStatus = null;

        if (isset($matchStamp['status'])) {
            $stampStatus = intval($matchStamp['status']);
            if ($stampStatus === 2) {
                $stampStatus = 'out';
            } elseif ($stampStatus === 1) {
                $stampStatus = 'in';
            } else {
                $stampStatus = 'unknown';
            }
        }

        return [
            'uid' => $matchUid['uid'],
            'username' => $match['username'] ?? null,
            'stampStatus' => $stampStatus,
        ];
    }

    /**
     * Parse worktime table and return stamp in/outs and daysum
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getWorkTime(): array
    {
        $this->checkAuthentication();

        $workTimeIndex = $this->client->request('GET', '/worktime/index?worktime=0');
        $html = $workTimeIndex->getBody();
        $regex = '#<tr class="(.*) confirmed" .*\s.*\s.*?<td class="worktime-table-body-in">(?<stampin>[\d\.\s:]+)<\/td>\s.*<td class="worktime-table-body-out">(?<stampout>[\d\.\s:]+|None).*\s.*<td class="worktime-table-body-daysum">(?<daysum>.*?)<\/td>\s+<td class="worktime-table-head-sumday">(?<monthsum>.*?)<\/td>#';

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

    /**
     * Get login status information
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOfficeLoginStatusInformation(): array
    {
        $this->checkAuthentication();

        $loginStatus = $this->client->request('GET', '/overview/index');
        $regex = '#<tr class=".*?">\s+<td>(?<username>.*?)<\/td>\s+<td>(?<team>.*?)<\/td>\s+<td>.*\s+<td class=\'.*?\'>(?<status>\w+)<\/td>#';
        $loginStatuses = [];

        if (false != preg_match_all($regex, $loginStatus->getBody(), $match)) {
            for ($i = 0, $count = count($match['username']); $i < $count; ++$i) {
                $loginStatuses[] = [
                    'username' => $match['username'][$i],
                    'team' => $match['team'][$i],
                    'status' => $match['status'][$i],
                ];
            }
        }

        return $loginStatuses;
    }

    /**
     * Get office users information
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getOfficeUsers(): array
    {
        $loginStatus = $this->client->request('GET', '/overview/index');
        $officeUsers = [];
        $regex = '#<select.*name="userid".*?>.*?<\/select>#s';

        if( false !== preg_match($regex, $loginStatus->getBody(), $match) ) {
            preg_match_all('#<option value="(?<userid>\d{2,5}?)">(?<username>.*?)<\/option>#s', $match[0], $users);

            for ($i = 0, $count = count($users['username']); $i < $count; ++$i) {
                $officeUsers[] = [
                    'id' => intval($users['userid'][$i]),
                    'name' => $users['username'][$i],
                ];
            }
        }

        return $officeUsers;
    }

    /**
     * Stamp in the user
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function stampIn(): array
    {
        $stampIn = $this->client->request('GET', 'stampinout/index/status/2');

        return json_decode($stampIn->getBody(), true);
    }

    /**
     * Stamp out the user
     *
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function stampOut(): array
    {
        $stampIn = $this->client->request('GET', 'stampinout/index/status/1');

        return json_decode($stampIn->getBody(), true);
    }

    /**
     * Check if user has been logged in
     */
    private function checkAuthentication(): void
    {
        if (false == $this->isAuthenticated()) {
            throw new AuthenticationRequiredException();
        }
    }
}
