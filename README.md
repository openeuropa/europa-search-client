# Europa Search Client
[![Build Status](https://drone.fpfis.eu/api/badges/openeuropa/europa-search-client/status.svg)](https://drone.fpfis.eu/openeuropa/europa-search-client)
[![Packagist](https://img.shields.io/packagist/v/openeuropa/europa-search-client.svg)](https://packagist.org/packages/openeuropa/europa-search-client)

## Description

_Europa Search Client_ is library offering a PHP API to consume Europa Search services.

## Install

Use [Composer](https://getcomposer.org/) to install the package:

```bash
$ composer require openeuropa/europa-search-client
```

## Usage

All calls should be done by instantiating the client class:

```php
require_once 'vendor/autoload.php';

$client = new \OpenEuropa\EuropaSearchClient\Client(
    new \GuzzleHttp\Client(),
    new \Http\Factory\Guzzle\RequestFactory(),
    new \Http\Factory\Guzzle\StreamFactory(),
    new \Http\Factory\Guzzle\UriFactory(),
    [
        'apiKey' => 'foo',
        'searchApiEndpoint' => 'https://example.com/search',
    ]
);
```

In the above example, we're passing the Guzzle HTTP client, request, stream and URI factories. But these can be replaced by any similar factories that are implementing the PSR interfaces. The last parameter is the configuration, whose values should be provided by the _Europa Search_ team. The possible values are:

- `apiKey` (string): Used by the Search and Ingestion APIs.
- `database` (string): Used by Ingestion API.
- `consumerKey` (string): Used by Ingestion API.
- `consumerSecret` (string): Used by Ingestion API.
- `searchApiEndpoint` (string, valid URI): The endpoint for Search API.
- `textIngestionApiEndpoint` (string, valid URI): Used by Ingestion API to ingest text.
- `fileIngestionApiEndpoint` (string, valid URI): Used by Ingestion API to ingest files.
- `deleteApiEndpoint` (string, valid URI): Used by Ingestion API to delete a document from the index.

### Searching

```php
$response = $client->search('something to search');
```

The search can be fine-tuned by passing additional arguments. Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::search()` for a full list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Search` object.

### Ingesting

#### Text

```php
$response = $client->ingestText('http://example.com/page/to/be/ingested', 'text to be ingested/index');
```

Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::ingestText()` for a complete list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Ingestion` object. 

#### File

```php
$client->ingestFile(@todo);
```

Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::ingestFile()` for a complete list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Ingestion` object.

#### Delete document

```php
$success = $client->deleteDocument('referenceID');
```

Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::deleteDocument()` for a complete list of parameters. The function returns a boolean indicating if the operation was successful.

### Checking availability

```php
$client->ping(@todo);
```
