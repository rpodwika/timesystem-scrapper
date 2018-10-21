<?php

namespace Rpodwika\TimesystemScrapper\Domain\Worktime;

/**
 * Class Worktime
 *
 * @package Domain\Worktime
 */
class Worktime
{
    /**
     * @var Stamp[]
     */
    private $stamps = [];

    /**
     * @var float
     */
    private $remainingHoursDay;

    /**
     * @var float
     */
    private $balance;

    /**
     * @param Stamp $stamp
     */
    public function addStamp(Stamp $stamp)
    {
        $this->stamps[] = $stamp;
    }

    public function getStamps(): array
    {
        return $this->stamps;
    }
}