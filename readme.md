# Timesystem scrapper 
______________________

The tool for scrapping the data from Timesystem


## How tu run?

* `docker-compose up -d`
* `docker-compose exec php composer install`

## How to use?

```
<?php 
require_once('vendor/autoload.php');

use GuzzleHttp\Client;
use Rpodwika\TimesystemScrapper\Scrapper\Timesystem;
use Rpodwika\TimesystemScrapper\Timesystem\{
    TimesystemHttpClient, Credentials
};

$client = new Client([
            'base_uri' => 'https://timesystem-url',
            'timeout' => 2.0,
            'cookies' => true,
]);

$timesystemHttpClient = new TimesystemHttpClient($client);

$timesystemHttpClient->authenticate(new Credentials("username", "pass"));

$timesystem = new Timesystem($timesystemHttpClient);

$timesystem->getWorktime();
$timesystem->getLoggedUserInformation();
$timesystem->getOfficeLoginStatus();

```

## Tests

docker-compose exec php bash ./vendor/bin/phpunit