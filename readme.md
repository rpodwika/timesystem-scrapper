# Timesystem scrapper 
______________________

The tool for scrapping the data from Timesystem

## How to use?

```
use GuzzleHttp\Client;
use Rpodwika\TimesystemScrapper\Scrapper\Timesystem;
use Rpodwika\TimesystemScrapper\Timesystem\{
    TimesystemHttpClient, Credentials
};

$client = new Client([
            'base_uri' => 'https://timesystem.sportradar.ag',
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

