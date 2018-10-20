<?php
/**
 * Created by PhpStorm.
 * User: robertpodwikamac
 * Date: 20.10.2018
 * Time: 16:04
 */

namespace Domain\Worktime;


class Worktime
{
    /**
     * @var Stamp[]
     */
    private $stamp = [];

    private $remainingHoursDay;
    private $balance;

    /**
     * @param Stamp $stamp
     */
    public function addStamp(Stamp $stamp)
    {
        $this->stamp[] = $stamp;
    }
}