<?php

namespace Rpodwika\TimesystemScrapper\Timesystem;

/**
 * Class Credentials
 *
 * @package Rpodwika\TimesystemScrapper\Timesystem
 */
class Credentials
{
    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * Credentials constructor.
     *
     * @param string $login
     * @param string $password
     */
    public function __construct(string $login, string $password)
    {
        if (empty($login) || empty($password)) {
            throw new \InvalidArgumentException("Neither login nor password cannot be empty");
        }

        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}