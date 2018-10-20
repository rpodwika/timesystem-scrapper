<?php

namespace Rpodwika\TimesystemScrapper\Scrapper;

use Rpodwika\TimesystemScrapper\Timesystem\TimesystemHttpClient;

/**
 * Class Timesystem
 *
 * @package Scrapper
 */
class Timesystem
{
    /**
     * @var TimesystemHttpClient
     */
    private $timesystemClient;

    /**
     * Timesystem constructor.
     *
     * @param TimesystemHttpClient $timesystemClient
     */
    public function __construct(TimesystemHttpClient $timesystemClient)
    {
        $this->timesystemClient = $timesystemClient;
    }


    public function getWorktime()
    {
        $worktimeTable = $this->timesystemClient->getWorkTime();

        exit;
    }

}