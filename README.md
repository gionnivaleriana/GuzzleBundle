GuzzleBundle [![Build Status](https://travis-ci.org/gionnivaleriana/GuzzleBundle.svg?branch=master)](https://travis-ci.org/gionnivaleriana/GuzzleBundle)
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
/** @var \GuzzleHttp\Client $client */
$client = $this->get("guzzle");

/** @var \Kopjra\GuzzleBundle\OAuth\OAuthSubscriber $oauth */
$oauth = $this->get("guzzle_oauth");

$client->getEmitter()->attach(
        $oauth->config([
            'consumer_key'    => 'key',
            'consumer_secret' => 'secret',
            'signature_method' => $oauth::SIGNATURE_METHOD_HMAC
        ])
    );

/** @var \GuzzleHttp\Message\ResponseInterface $response */
$response = $client->get(
    'http://oauthbin.com/v1/request-token', 
    ['auth' => 'oauth']
);
```

#### Cache Subscriber - Client side

```php
/** @var \GuzzleHttp\Client $client */
$client = $this->get("guzzle");

/** @var \Kopjra\GuzzleBundle\Cache\CacheSubscriber $oauth */
$cache = $this->get("guzzle_cache");

$client->getEmitter()->attach($cache);

/** @var \GuzzleHttp\Message\ResponseInterface $response */
$response = $client->get('http://httpbin.org/cache/60');
```

#### Cache Subscriber - Server side

At the moment it only checks the existence of a cached copy, returning it no matter what.

```php
/** @var \GuzzleHttp\Client $client */
$client = $this->get("guzzle");

/** @var \Kopjra\GuzzleBundle\Cache\CacheSubscriber $oauth */
$cache = $this->get("guzzle_cache");

// Sets the cache type to server side
$cache->setCacheType("server");

$client->getEmitter()->attach($cache);

/** @var \GuzzleHttp\Message\ResponseInterface $response */
$response = $client->get('http://httpbin.org/cache/60');
```

#### Retry Subscriber

```php
/** @var \GuzzleHttp\Client $client */
$client = $this->get("guzzle");

/** @var \Kopjra\GuzzleBundle\Retry\RetrySubscriber $oauth */
$retry = $this->get("guzzle_retry");

// Sets the configuration for the subsrcriber
$retry->config([
    'filter' => $retry::createChainFilter([
        $retry::createIdempotentFilter(),
        // Retries only if the response header has a 304 code.
        $retry::createStatusFilter([304])
    ]),
    // Each retry will be delayed by 1000ms...
    'delay'  => function () { return 1000; },
    // ...for max 10 times.
    'max'    => 10
]);

$client->getEmitter()->attach($retry);

/** @var \GuzzleHttp\Message\ResponseInterface $response */
$response = $client->get('http://httpbin.org/status/304');
```

#### Services
More doc: [/Services/README.md](Services/README.md)

```php
/** @var \GuzzleHttp\Client $client */
$client = $this->get("guzzle");

/** @var \Kopjra\GuzzleBundle\Services\Services $services */
$services = $this->get("guzzle_services");

$webServices = $services->setWebServices(["webservice1"], ["webservice2"], ["webservice3"]);

/** @var \GuzzleHttp\Command\Guzzle\GuzzleClient $client */
$client = $services->attachWebService($client, $webService->webservice2);

$response = $client->foo(['bar' => 'baz']);
```

Content of the file `app/Resources/webservice/foo.json`
```json
{
  "baseUrl": "http:\/\/httpbin.org\/",
  "operations": {
    "foo": {
      "httpMethod": "GET",
      "uri": "\/get?{bar}",
      "responseModel": "getResponse",
      "parameters": {
        "bar": {
          "type": "string",
          "location": "query"
        }
      }
    }
  },
  "models": {
    "getResponse": {
      "type": "object",
      "additionalProperties": {
        "location": "json"
      }
    }
  }
}
```

Readings
--------

 * [Configuration Reference](https://github.com/gionnivaleriana/GuzzleBundle/tree/master/Resources/doc/configuration-reference.rst)

----

Authors
=======
* ![Emanuele Minotto](https://avatars0.githubusercontent.com/u/417201?s=15) [Emanuele Minotto](https://github.com/emanueleminotto)
* ![Joy Lazari](https://avatars0.githubusercontent.com/u/6898095?s=15) Joy Lazari ([Gionni Valeriana](https://github.com/gionnivaleriana))

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/gionnivaleriana/guzzlebundle/trend.png)](https://bitdeli.com/free "Bitdeli Badge")


License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
