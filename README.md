# PHP Client for Tracking3.io Core API


### Install

Add the following repository to your `composer.json`
```
{
    // ..

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Halanson/tracking3-core-php-client.git"
        }
    ],
        
    // ..
}
```

Run composer
```shell script
composer require tracking3/tracking3-core-php-client
```

### Usage

```php
use Tracking3\Core\Client\Client;
use Tracking3\Core\Client\Configuration;

$client = new Client(
    new Configuration(
        [
            'password' => 's3cr37',
            'email' => 'john@example.com',
            'applicationId' => 'my-custom-application-id',
        ]
    )
);

// will return an object of Organisation
$organisation = $client->organisation()->getOrganisation($idOrganisation);

// will return an array
$organisationArray = $client->organisation()->getOrganisation($idOrganisation, false); 
```

### Configuration

```php
use Tracking3\Core\Client\Configuration;
use Tracking3\Core\Client\EnvironmentHandlingService;

$config = new Configuration([
    'password' => 's3cr37', // required
    'email' => 'john@example.com', // required
    'applicationId' => null, // default value
    'doAutoLogin' => true, // default value
    'timeout' => 60, // default value
    'apiVersion' => EnvironmentHandlingService::API_VERSION, // default value
    'environment' => EnvironmentHandlingService::ENV_PRODUCTION, // default value
]);
```

| Configuration | Description |
|:---|:---|
| password (string) | (required) The password for your account. |
| email (string) | (required) The email address you have used for sign up. |
| applicationId (string) | This is custom string that, if set, will be added to the request headers. This way you can later analyse where the request came from. (default: null) |
| doAutoLogin (bool) | If *true*, the client will immediately request an refresh and access token with the given email and password. If *false* you have to take care on login by yourself. (default: true) |
| timeout (int) | Seconds before a timeout exception is raised (default: 60) |
| apiVersion (string) | The API version that is used for every request. Pay attention, this value may change over time when upgrading to a newer client version. (default: EnvironmentHandlingService::API_VERSION) |
| environment (string) | The API environment, currently only *production* is supported .(default: EnvironmentHandlingService::ENV_PRODUCTION) |
