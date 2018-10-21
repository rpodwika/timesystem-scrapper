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
     * Stamp constructor.
     * @param $in
     * @param $out
     */
    public function __construct(\DateTime $in, ?\DateTime $out = null)
    {
        $this->in = $in;
        $this->out = $out;
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