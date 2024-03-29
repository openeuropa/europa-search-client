# Europa Search Client
[![Build Status](https://drone.fpfis.eu/api/badges/openeuropa/europa-search-client/status.svg)](https://drone.fpfis.eu/openeuropa/europa-search-client)
[![Packagist](https://img.shields.io/packagist/v/openeuropa/europa-search-client.svg)](https://packagist.org/packages/openeuropa/europa-search-client)

## Description

_Europa Search Client_ is a library offering a PHP API to consume Europa Search services.

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
        // For a full list of options see "Configuration".
        'apiKey' => 'foo',
        'searchApiEndpoint' => 'https://example.com/search',
    ]
);
```

In the above example, we're passing the Guzzle HTTP client, request, stream and URI factories. But these can be replaced by any similar factories that are implementing the PSR interfaces. The last parameter is the configuration.

### Configuration

Possible configurations:

- `apiKey` (string): Used by the Search and Ingestion APIs.
- `database` (string): Used by Ingestion API.
- `infoApiEndpoint` (string, valid URI): The Search API info endpoint.
- `searchApiEndpoint` (string, valid URI): The Search API endpoint.
- `facetApiEndpoint` (string, valid URI): The Search API facets endpoint.
- `tokenApiEndpoint` (string, valid URI): The endpoint for Authorisation/Token API.
- `consumerKey` (string): Used by Authorisation/Token API.
- `consumerSecret` (string): Used by Authorisation/Token API.
- `textIngestionApiEndpoint` (string, valid URI): The Ingestion API endpoint to ingest text.
- `fileIngestionApiEndpoint` (string, valid URI): The Ingestion API endpoint to ingest files.
- `deleteApiEndpoint` (string, valid URI): The Ingestion API endpoint to delete a document from the index.

### Server info

```php
$response = $client->getInfo();
```

Will return information about Europa Search server availability and API version.

### Searching

#### Simple

```php
$response = $client->search('something to search');
```

The search can be fine-tuned by passing additional arguments. Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::search()` for a full list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Search` object.

#### Facets

```php
$response = $client->getFacets('something to search');
```

The facets search can be fine-tuned by passing additional arguments. Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::getFacets()` for a full list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Facets` object.

### Ingesting

#### Text

```php
$response = $client->ingestText('http://example.com/page/to/be/ingested', 'text to be ingested/index');
```

Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::ingestText()` for a complete list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Ingestion` object. 

#### File

```php
$binaryString = file_get_contents(...);
$client->ingestFile('http://example.com/file/to/be/ingested', $binaryString);
```

Check `\OpenEuropa\EuropaSearchClient\Contract\ClientInterface::ingestFile()` for a complete list of parameters. The response is an `\OpenEuropa\EuropaSearchClient\Model\Ingestion` object.

#### Delete document

```php
$success = $client->deleteDocument('referenceID');
```

The function returns a boolean indicating if the operation was successful.

## Contributing

Please read [the full documentation](https://github.com/openeuropa/openeuropa) for details on our code of conduct,
and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. 
