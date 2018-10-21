<?php

namespace Rpodwika\TimesystemScrapper\Scrapper;

use Rpodwika\TimesystemScrapper\Domain\Worktime\{
    Stamp, Worktime
};
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

    /**
     * @return Worktime
     */
    public function getWorktime(): Worktime
    {
        $worktimeData = $this->timesystemClient->getWorkTime();
        $workTime = new Worktime();

        for ($i = 0, $count = count($worktimeData['stampIn']); $i < $count; ++$i) {
            if (0 == intval($worktimeData['stampIn'][$i])) {
                continue;
            }

            $stampIn = new \DateTime($worktimeData['stampIn'][$i]);
            $stampOut = !empty($worktimeData['stampOut'][$i]) ? new \DateTime($worktimeData['stampOut'][$i]) : null;

            $workTime->addStamp(new Stamp($stampIn, $stampOut));

        }

        return $workTime;
    }

    /**
     * @return array
     */
    public function getLoggedUserInformation()
    {
        return $this->timesystemClient->getUserInformation();
    }

    /**
     * @return array
     */
    public function getOfficeLoginStatus()
    {
        return $this->timesystemClient->getOfficeLoginStatusInformation();
    }
}