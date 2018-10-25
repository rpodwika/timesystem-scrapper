<?php

namespace Rpodwika\TimesystemScrapper\Domain\Worktime;

/**
 * Class Stamp
 *
 * @package Domain\Worktime
 */
class Stamp
{
    /**
     * @var \DateTime
     */
    private $in;

    /**
     * @var \DateTime|null
     */
    private $out;

    /**
     * @var float
     */
    private $sumday;

    /**
     * Stamp constructor.
     *
     * @param $sumday
     * @param \DateTime $in
     * @param \DateTime|null $out
     */
    public function __construct($sumday, \DateTime $in, ?\DateTime $out = null)
    {
        $this->sumday = $sumday;
        $this->in = $in;
        $this->out = $out;
    }

    /**
     * @return float
     */
    public function getSumday()
    {
        return $this->sumday;
    }

    /**
     * @return \DateTime
     */
    public function getIn(): \DateTime
    {
        return $this->in;
    }

    /**
     * @return \DateTime|null
     */
    public function getOut(): ?\DateTime
    {
        return $this->out;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'in' => $this->in->format('Y-m-d'),
            'out' => null !== $this->out ? $this->out->format('Y-m-d') : null,
        ];
    }
}
