GuzzleBundle
============

Kopjra Guzzle 4 Bundle

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

### TO DO List

- [x] OAuth Subscriber
- [x] Cache Subscriber - Client side
- [ ] Cache Subscriber - Server side
- [ ] Retry Subscriber
- [ ] Guzzle Services
