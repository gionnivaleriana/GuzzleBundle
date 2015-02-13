## Usage

### Load the service

```php
/** @var \GuzzleHttp\Client $client */
$client = $this->get("guzzle");

/** @var \Kopjra\GuzzleBundle\Services\Services $services */
$services = $this->get("guzzle_services");
```

### Attach a web service on the fly

```php
/** @var array $webService */
$webService = [
    'baseUrl' => 'http://httpbin.org/',
    'operations' => [
        'foo' => [
            'httpMethod' => 'GET',
            'uri' => '/get?{bar}{baz}',
            'responseModel' => 'getResponse',
            'parameters' => [
                'bar' => [
                    'type' => 'string',
                    'location' => 'query'
                ]
            ]
        ]
    ],
    'models' => [
        'getResponse' => [
            'type' => 'object',
            'additionalProperties' => [
                'location' => 'json'
            ]
        ]
    ]
];

/** @var \GuzzleHttp\Command\Guzzle\GuzzleClient $client */
$client = $services->attachWebService($client, $webService);

$response = $client->foo(['bar' => 'baz']);
```

Running the above sample code should return an array like this:
```
Array
(
    [args] => Array
        (
            [bar] => baz
        )

    [headers] => Array
        (
            [Connect-Time] => 2
            [Connection] => close
            [Host] => httpbin.org
            [Total-Route-Time] => 0
            [User-Agent] => Guzzle/5.1.0 curl/7.35.0 PHP/5.5.9-1ubuntu4.5
            [Via] => 1.1 vegur
            [X-Request-Id] => b2990c34-5d2b-4f05-8d7f-6a6ebe7de506
        )

    [origin] => 151.42.89.214
    [url] => http://httpbin.org/get?bar=baz
)
```

### Attach a web service/s loaded from remote file/s

#### Custom path for remote files
By default the location of the remote webservice configuration files is `your/project/path /app/Resources/webservice/`

You can customize by setting a custom path from app/ folder

```php
// Means your/project/path /app/ your/custom/path/
$services->config(["path" => "your/custom/path"]);
```

#### The web service file
Content of the file `app/Resources/webservice/facebook.json`
```json
{
  "baseUrl": "https:\/\/graph.facebook.com",
  "operations": {
    "getUser": {
      "httpMethod": "GET",
      "uri": "\/{id}",
      "responseModel": "getResponse",
      "parameters": {
        "id": {
          "type": "string",
          "location": "uri"
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

#### Setting the web service
Set the web server specifing the name of the file, the type|extension (json|xml, default: json) and an optional custom path just for this file

```php
$webServices = $services->setWebServices(
    ["twitter"], ["facebook"], ["youtube"]
    "json"
    $custom_path
);

/** @var \GuzzleHttp\Command\Guzzle\GuzzleClient $client */
$client = $services->attachWebService($client, $webService->facebook);

$response = $client->getUser(['id' => '20531316728']);
```

Running the above sample code should return an array like this:

```
Array
(
    [id] => 20531316728
    [name] => Facebook
    [link] => https://www.facebook.com/facebook
    [category] => Product/service
    [founded] => February 4, 2004
    [likes] => 168347941
    ...
)
```