GuzzleBundle
============

A Guzzle 5 Bundle

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require Kopjra/GuzzleBundle "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new Kopjra\GuzzleBundle\KopjraGuzzleBundle(),
        );

        // ...
    }

    // ...
}
```

Usage
=====

#### OAuth Subscriber

```php
$client = $this->get("guzzle");

$oauth = $this->get("guzzle_oauth");

$oauth->config([
    'consumer_key'    => '...',
    'consumer_secret' => '...',
    'token'           => '...',
    'token_secret'    => '...'
]);

$client->getEmitter()->attach($oauth);

$response = $client->get(
    'https://api.twitter.com/1.1/statuses/home_timeline.json', 
    ['auth' => 'oauth']
);
```

#### Cache Subscriber - Client side

```php
$client = $this->get("guzzle");

$cache = $this->get("guzzle_cache");

$client->getEmitter()->attach($cache);

$response = $client->get('http://httpbin.org/cache/60');
```

#### Cache Subscriber - Server side

At the moment it only checks the existence of a cached copy, returning it no matter what.

```php
$client = $this->get("guzzle");

$cache = $this->get("guzzle_cache");

// Sets the cache type to server side
$cache->setCacheType("server");

$client->getEmitter()->attach($cache);

$response = $client->get('http://httpbin.org/cache/60');
```

## TO DO List

- [x] OAuth Subscriber
- [x] Cache Subscriber - Client side
- [x] Cache Subscriber - Server side
- [x] Retry Subscriber
- [ ] Guzzle Services

----

Authors
=======
* ![Emanuele Minotto](https://avatars0.githubusercontent.com/u/417201?s=15) [Emanuele Minotto](https://github.com/emanueleminotto)
* ![Joy Lazari](https://avatars0.githubusercontent.com/u/6898095?s=15) Joy Lazari ([Gionni Valeriana](https://github.com/gionnivaleriana))