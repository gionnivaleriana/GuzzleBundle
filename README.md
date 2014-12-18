GuzzleBundle
============

Kopjra Guzzle 5 Bundle

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

### TO DO List

- [x] OAuth Subscriber
- [x] Cache Subscriber - Client side
- [ ] Cache Subscriber - Server side
- [x] Retry Subscriber
- [ ] Guzzle Services
