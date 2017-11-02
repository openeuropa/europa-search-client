# Europa Search Client Library

[![Build Status](https://travis-ci.org/ec-europa/oe-europa-search-client.svg?branch=master)](https://travis-ci.org/ec-europa/oe-europa-search-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ec-europa/oe-europa-search-client/badges/quality-score.png?b=issue-12-nept-1461)](https://scrutinizer-ci.com/g/ec-europa/oe-europa-search-client/?branch=issue-12-nept-1461)

The Europa Search Client Library aims to hide Europa Search services complexity behind a 
easy-to-use client library so that users don't have to worry about building their own 
request messages nor implementing REST interactions.

Table of content:
=================
- [How to use the library](#library-use)
- [Architectural overview](#architectural-overview)
- [Quality control](#quality-control)
- [Testing](#testing)
- [Dependencies](#dependencies)

[Go to top](#table-of-content)

## Library use

In order to send a request to one of the REST services of Europa Search, 3 elements are necessary:
- An array with the parameters defining the library configuration.
- The objects contained in the `EC\EuropaSearch\Messages` package in order to define the messages to send to 
  the services;
- `EC\EuropaSearch\EuropaSearch` that initializes and orchestrates all library's services and dependencies.<br />
  This is the main entry point for host applications.

### Parameters array

It allows defining the library settings that will be used by the different layers.
 
Its constructor requires an array with the following items:
- 'indexing_settings': lists the parameters required to connect to the Indexing REST services (Ingestion API).<br />
  These parameters are:
  - 'url_root': The root URL of the REST services; I.E. without the REST service path;
  - 'api_key': The Ingestion API key supplied by the Europa Search team;
  - 'database': The database value supplied by the Europa Search team.
- 'search_settings': lists the parameters required to connect to the Search REST services (Search API).<br />
  These parameters are:
  - 'url_root': The root URL of the REST services; I.E. without the REST service path;
  - 'api_key': The Search API key supplied by the Europa Search team.
- 'services_settings': (optional) lists the parameters required by the library's components.<br />
  These parameters are:
  - 'logger': The logger object that must be used by the library to record the log messages.<br />
    The chosen object must implement the PSR-3 Interface.
  - 'log_level': The level of the log messages to record.<br />
    The accepted values for this parameter is those defined by the `Psr\Log\LogLevel` class.
    
Example:

If the Europa Search gives the following data:
- url root for the Ingestion API REST services is: `http://www.es-ingestion/ingestion-api`;
- api key for the Ingestion API REST services is: aaa-eifnzff-blabla-0828
- database name: TEST-DUMMY-EXAMPLE
- url root for the search API REST services is: `http://www.es-search/searching-api`;
- api key for the search API REST services is: aaa-eifnzff-blabla-0828

And you want to use your PSR-3 Logger object for recording log messages.

The array must be defined as follow:
```php
$clientConfiguration = [
  'indexing_settings' => [
      'url_root' => 'http://www.es-ingestion/ingestion-api',
      'api_key' => 'aaa-eifnzff-blabla-0828',
      'database' => 'TEST-DUMMY-EXAMPLE',
  ],
  'search_settings' => [
     'url_root' => 'http://www.es-search/searching-api',
     'api_key' => 'aaa-eifnzff-blabla-0828',
  ],
  'services_settings' => [
    'logger' => new Logger(),
    'log_level' => LogLevel::DEBUG,
  ],
];

```

### Message objects

```
TODO
```

### `EuropaSearch` object

As already said sooner, It is the entry point for the host applications like Drupal. From it, the ingestion and search services.

To call an ingestion or a search service, proceed as follow:
1. Instantiate the object with the array of configuration parameters.<br />
   If we use the parameters array from the example of the "[Parameters array](#parameters-array)" section, we get:<br />
   ```php
   $factory = new EuropaSearch($clientConfiguration);
   ```
2. Call the proper application instance:
   - For an ingestion request:<br />
     ```php
     $indexApp = $factory->getIndexingApplication();
     ```
   - For a search request:<br />
     ```php
     $searchApp = $factory->getSearchApplication();
     ```
3. Send the proper message through the application instance:
   - For an ingestion request, if we use the `EC\EuropaSearch\Messages\Index\IndexingWebContent` from the example of 
     the "[Message objects](#message-objects)" section:<br />
     ```php
     $response = $indexApp->sendMessage($webContentMessage);
     ```
 
     `$response` is an `EC\EuropaSearch\Mssages\Index\IndexingResponse` object containing 
     the indexed reference and a tracking id returned by the REST services.

   - For a search request, if we use the `EC\EuropaSearch\Messages\Search\SearchMessage` from the example of 
     the "[Message objects](#message-objects)" section:<br />
     ```php
     $response = $searchApp->sendMessage($searchMessage);
     ```
 
     `$response` is an `EC\EuropaSearch\Mssages\Search\SearchResponse` object containing 
     search results and some other data related to the current search like the total number of results.

## Architectural overview

### Architecture in layers

The library is organized into 3 layers with a specific scope:
- **Applications layer**: it is called by 3rd party systems like Drupal.<br />  
  It is in charge of 
  - Receiving the request messages to send the Europa Search REST services;
  - Validating the message content before continue the process.
- **Proxies layer**: it is in charge of 
  - Converting the received message into a request that will be sent to the REST services;
  - Routing the request to the right transporter service in order that it sends the request;
  - Returning the REST services responses to the Applications layer.
- **Transporters layer**: it is the layer that manages the requests to the REST services.
  
To have more information about these layers, please consult the [package documentation](src/EuropaWS/docs/00-introduction.md).



## Quality control

The automatic quality control is managed by the ["OpenEuropa code review"](https://github.com/ec-europa/oe-code-review) component.
 
The component depends on [GrumPHP](https://github.com/phpro/grumphp) and based its controls on the Drupal coding convention.

Check the ["OpenEuropa code review" documentation](https://github.com/ec-europa/oe-code-review/blob/master/README.md) for more.

### Component's Usage

GrumPHP tasks will be ran at every commit, if you want to run them without performing a commit use the following command:

```
$ ./vendor/bin/grumphp run
```

If you want to simulate a commit message use:

```
$ ./vendor/bin/grumphp git:pre-commit
```

Check [GrumPHP documentation](https://github.com/phpro/grumphp/tree/master/doc) for more.

## Testing

For testing the client process, [PHPUnit](https://phpunit.de) has been used to define different unit tests. 

All tests are located in the [tests/src](tests/src) repository and can be run with the following command line:
```
vendor/bin/phpunit
```
The basic configuration of PHPUnit environment is defined in [phpunit.xml.dist](phpunit.xml.dist)

## Dependencies:

The client library depends on the following projects:
- [Symfony DependencyInjection](https://symfony.com/components/DependencyInjection) component: instantiates services and 
  manages their dependencies.
- [Symfony Validator](https://symfony.com/doc/current/components/validator.html) component: provides tools to validate
  message objects and their components.
- [Symfony Config](https://symfony.com/components/Config) and [Symfony Yaml](https://symfony.com/components/Yaml) components: 
  loads the client services configuration stored in YMl files.
- [GuzzleHTTP v6](http://guzzle.readthedocs.io/en/latest/) = manages the request sending to REST services.  
