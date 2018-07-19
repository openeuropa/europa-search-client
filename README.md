# Europa Search Client Library
[![Build Status](https://drone.fpfis.eu/api/badges/openeuropa/europa-search-client/status.svg)](https://drone.fpfis.eu/openeuropa/europa-search-client/)

The Europa Search Client Library aims to hide Europa Search services complexity behind a 
easy-to-use client library so that users don't have to worry about building their own 
request messages nor implementing REST interactions.

Table of content:
=================
- [How to use the library](#library-use)
- [Architectural overview](#architectural-overview)
- [Quality control](#quality-control)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Dependencies](#dependencies)


## Library use

In order to send a request to one of the REST services of Europa Search, 3 elements are necessary:
- An array with the parameters defining the library configuration.
- The objects contained in the `OpenEuropa\EuropaSearch\Messages` package in order to define the messages to send to
  the services;
- `OpenEuropa\EuropaSearch\EuropaSearch` that initializes and orchestrates all library's services and dependencies.<br />
  This is the main entry point for host applications.

### Parameters array

It allows defining the library settings that will be used by the different layers.
 
It requires the following items:
- 'indexing_settings': lists the parameters required to connect to the Indexing REST services (Ingestion API).<br />
  These parameters are:
  - 'url_root': The root URL of the REST services; I.E. without the REST service path;
  - 'api_key': The Ingestion API key supplied by the Europa Search team;
  - 'database': The database value supplied by the Europa Search team.
  - 'proxy': The proxy settings to use with the HTTP request. **If the host proxy settings allows using the client,
    it can be skipped**; otherwise the following parameters can be set:
    - 'proxy_configuration_type': string the proxy type
      to use with application requests. The possible values are:
      - 'default': The client must use the host proxy
         settings to send requests;
      - 'custom': The client must use a dedicated proxy
         to send requests; Then the 'custom_proxy_address' is
         mandatory.
      - 'none': The client must bypass the proxy to send requests;
      - 'user_name': string the proxy credentials username;<br />
        It is only to be set if 'proxy_configuration_type' parameter value is 'custom' AND if the custom proxy requires
        it.
    - 'user_password': string the proxy credentials
      password;<br />
      It is only to be set if 'proxy_configuration_type' parameter value is 'custom' AND if the custom proxy requires 
      it.
    - 'custom_proxy_address': string the URL of the proxy to use;
      It is only **MANDATORY if the 'proxy_configuration_type'** parameter value is 'custom';
- 'search_settings': lists the parameters required to connect to the Search REST services (Search API).<br />
  These parameters are:
  - 'url_root': The root URL of the REST services; I.E. without the REST service path;
  - 'api_key': The Search API key supplied by the Europa Search team.
  - 'proxy': The proxy settings to use with the HTTP request. **If the host proxy settings allows using the client,
    it can be skipped**; otherwise the following parameters can be set:
    - 'proxy_configuration_type': string the proxy type
      to use with application requests. The possible values are:
      - 'default': The client must use the host proxy
         settings to send requests;
      - 'custom': The client must use a dedicated proxy
         to send requests; Then the 'custom_proxy_address' is
         mandatory.
      - 'none': The client must bypass the proxy to send requests;
    - 'user_name': string the proxy credentials username;<br />
      It is only to be set if 'proxy_configuration_type' parameter value is 'custom' AND if the custom proxy requires
      it.
    - 'user_password': string the proxy credentials
       password;<br />
       It is only to be set if 'proxy_configuration_type' parameter value is 'custom' AND if the custom proxy requires 
       it.
    - 'custom_proxy_address': string the URL of the proxy to use;
       It is only **MANDATORY if the 'proxy_configuration_type'** parameter value is 'custom';
- 'services_settings': (optional) lists the parameters required by the library's components.<br />
  These parameters are:
  - 'logger': The logger object that must be used by the library to record the log messages.<br />
    The chosen object must implement the PSR-3 Interface:`Psr\Log\LoggerInterface`.
  - 'log_level': The level of the log messages to record.<br />
    The accepted values for this parameter are those defined by the `Psr\Log\LogLevel` class.
    
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

#### Messages for indexing

So far, there are 2 types of messages for sending indexing requests.

##### IndexWebContentMessage

This message object allows defining the message representing an **indexing request for a web content**. 
To learn more about its structure, please consult the [API documentation](#api-documentation).

##### IndexFileMessage (Not completely implemented yet)

This message object allows defining the message representing an **indexing request for a file**, when this part of the client will 
be implemented.

##### DeleteIndexItemMessage

This message object allows defining the message representing an **deletion request for an indexed item**. 

In the current implementation, the object diverts from IndexWebContentMessage or IndexFileMessage on the mandatory properties. 
Only the document id (setDocumentId) is mandatory; The other properties can be omitted.

To learn more about its structure, please consult the [API documentation](#api-documentation).

##### Component objects: the Metadata .

This section only concerns the adding of the updating of the Europa Search index with the `IndexWebContentMessage` and `IndexFileMessage`
objects.

Each document (web content or file) sent for indexing are characterized by metadata that form the components of the indexing messages.

There are 7 types:
- Boolean (`BooleanMetadata`): accepts boolean value(s) (true/false);
- Date (`DateMetadata`): accepts date value(s);
- Float (`FloatMetadata`): accepts float/double value(s);
- Full text (`FullTextMetadata`): accepts string value(s) that will be screened by the full-text search;
- Integer (`IntegerMetadata`): accepts integer value(s);
- Not indexed (`NotIndexedMetadata`): accepts string value(s) that will be stored in the Europa Search system but not in the search index;
- String (`StringMetadata`): accepts string value(s) that will be used as search filters;
- URL (`URLMetadata`): accepts URL value(s);

For more information about the related classes, read the [API documentation](#api-documentation).

As the library supports the Dynamic schema of Europa Search, the metadata names can be those already used in the consumer system.<br />
The client service via its proxy layer ensures that the names are formatted correctly before sending the request; I.E. 
the names are prefixed with the sequence declaring its type.<br />
For instance, a string metadata name in the system is `blabla`, it will become `esST_blabla` when it is sent.

##### Example

```php
$webContentMessage = new IndexWebContentMessage();
$indexedDocument->setDocumentURI('http://europa.test.com/content.html');
$indexedDocument->setDocumentContent('<div id="lipsum">
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus tempor mattis sem vitae egestas. Nulla sed mauris sed ante convallis scelerisque. Vestibulum urna nisl, aliquam non risus vel, varius commodo augue. Aliquam efficitur elementum dapibus. Aliquam erat volutpat. Nulla orci purus, ultricies non velit at, venenatis fringilla ipsum. Sed porta nunc sit amet felis semper, at tempor erat dapibus. Sed id ipsum enim. Mauris suscipit pharetra lacinia. In nisi sem, tincidunt ac vestibulum ut, ultrices sed nisi. Phasellus nec diam at libero suscipit consequat. Nunc dapibus, ante ac hendrerit varius, sapien ex consequat ante, non venenatis ipsum metus eu ligula. Phasellus mattis arcu ut erat vulputate, sit amet blandit magna egestas. Vivamus nisl ipsum, maximus non tempor nec, finibus eu nisl. Phasellus lacinia interdum iaculis.
</p>\n
<p>
Duis pellentesque, risus id efficitur convallis, elit justo sollicitudin elit, in convallis urna est id nibh. Sed rhoncus est nec leo hendrerit, ut tempus urna feugiat. Ut sed tempor orci, eu euismod massa. Phasellus condimentum sollicitudin ante, vel pretium mauris auctor quis. Etiam sit amet consectetur lorem. Phasellus at massa ex. Fusce porta est sit amet arcu pretium, ut suscipit eros molestie. Fusce malesuada ornare cursus. Curabitur sit amet eros nibh. Sed imperdiet magna quis odio tempus vehicula. Praesent auctor porta dolor, eu lacinia ante venenatis vel.
</p>\n
<p>
In diam tellus, sagittis sit amet finibus eget, ultrices sed turpis. Proin sodales dictum elit eget mollis. Aliquam nec laoreet purus. Pellentesque accumsan arcu vitae ipsum euismod, nec faucibus tellus rhoncus. Sed lacinia at augue vitae hendrerit. Aliquam egestas ante sit amet erat dignissim, non dictum ligula iaculis. Nulla tempor nec metus vitae pellentesque. Nulla porta sit amet lacus eu porttitor.
</p>\n
<p>
Nam consectetur leo eu felis vehicula sollicitudin. Aliquam pharetra, nulla quis tempor malesuada, odio nunc accumsan dui, in feugiat turpis ipsum vel tortor. Praesent auctor at justo convallis convallis. Aenean fringilla magna leo, et dictum nisi molestie sed. Quisque non ornare sem. Duis quis felis erat. Praesent rutrum vehicula orci ac suscipit.
</p>\n
<p>
Sed nec eros sit amet lorem convallis accumsan sed nec tellus. Maecenas eu odio dapibus, mollis leo eget, interdum urna. Phasellus ac dui commodo, cursus lorem nec, condimentum erat. Pellentesque eget imperdiet nisl, at convallis enim. Sed feugiat fermentum leo ac auctor. Aliquam imperdiet enim ac pellentesque commodo. Mauris sed sapien eu nulla mattis hendrerit ac ac mauris. Donec gravida, nisi sit amet rhoncus volutpat, quam nisl ullamcorper nisl, in luctus sapien justo et ex. Fusce dignissim felis felis, tempus faucibus tellus pulvinar vitae. Proin gravida tempus eros sit amet viverra. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum libero quis tellus commodo, non vestibulum lacus rutrum. Etiam euismod odio ipsum, nec pulvinar nisl ultrices sit amet. Nunc feugiat orci vel odio interdum, non dignissim erat hendrerit. Vestibulum gravida et elit nec placerat.
</p></div>');
$indexedDocument->setDocumentId('web_content_client_1');
$indexedDocument->setDocumentLanguage('en');

// Component definition for the message.

$metadata = new FullTextMetadata('title');
$metadata->setValues(['this the title']);
$indexedDocument->addMetadata($metadata);

$metadata = new StringMetadata('tag');
$metadata->setValues(['taxonomy term']);
$indexedDocument->addMetadata($metadata);

$metadata = new IntegerMetadata('rank');
$metadata->setValues([1]);
$indexedDocument->addMetadata($metadata);

$metadata = new FloatMetadata('percentage');
$metadata->setValues([0.1]);
$indexedDocument->addMetadata($metadata);
$metadata = new DateMetadata('publishing_date');
$metadata->setValues([date('F j, Y, g:i a', strtotime('11-12-2018'))]);
$indexedDocument->addMetadata($metadata);

$metadata = new URLMetadata('uri');
$metadata->setValues(['http://www.europa.com']);
$indexedDocument->addMetadata($metadata);
```

#### Messages for searching

So far, there is 1 type of messages.

##### SearchMessage

This message object allows defining the message representing a **search request**. To learn more about 
its structure, please consult the [API documentation](#api-documentation).

##### Component objects: the filters and queries components.

Each sent search request contains a query used for filtering the search results. This query is made of the different filter types 
that form the components of the search messages.<br />
Each available component represents a component of the Europa Search Search API syntax. 
To know more about, please consult the _"Advanced Search Parameters"_ page of the official documentation of Europa Search API.

There are 2 main types composed themselves of sub-types:
- The simple filters (Clause) that defines basic filter criteria:
   * exists (`FieldExists`): Defines a filter on the existence of a specific metadata set in a indexed document;
   * range (`Range`): Defines a filter on a specific metadata based on a range of dates or numbers;
   * term (`Term`): Defines a filter on a specific metadata based on a defined value;
   * terms (`Terms`): Defines a filter on a specific metadata based on a list of values;
- The combined filters (Query) that defines filter criteria based on a series of filters (clauses or queries) targeting different metadata:
   * Bool (`BooleanQuery`): Defines a filter query based on 3 aggregated filter as foreseen by the Europa Search query API:
     - 'must': Results MUST fulfill. it can be used to build "AND" equivalent where clause.
     - 'must_not': Results MUST NOT fulfill. it can be used to build "NOT" equivalent where clause.
     - 'should': Results SHOULD fulfill. it can be used to build "OR" equivalent where clause.
   * Boosting (`BoostingQuery`): Defines a filter query based on 2 aggregated filter as foreseen by the Europa Search query API:
     - 'positive': Items fulfilling query's criteria will be better placed in the result list.<br />
       It can contain `StringMetadata`, `URLMetadata`, `IntegerMetadata` or `FloatMetadata` only.
     - 'negative': Items fulfilling query's criteria will be less well placed in the result list.<br />
       It can contain `StringMetadata`, `URLMetadata`, `IntegerMetadata` or `FloatMetadata` only.

For more information about the related classes, read the [API documentation](#api-documentation).

As the library supports the Dynamic schema of Europa Search, the metadata names implies in the queries can be those already used in 
the consumer system.<br />
The client service via its proxy layer ensures that the names are formatted correctly before sending the request; I.E. 
the names are prefixed with the sequence declaring its type<br />
For instance, a string metadata name in the system is `blabla`, it will become `esST_blabla` when it is sent.

##### Example

```php
$searchMessage = new SearchMessage();
$searchMessage->setSearchedLanguages(['fr']);
$searchMessage->setHighLightParameters('<strong>{}</strong>', 250);
$searchMessage->setPagination(20, 1);
$searchMessage->setSearchedText('Lorem ipsum');

// Query Component definition for the message.

$booleanQuery = new BooleanQuery();
$filter = new RangeClause(new IntegerMetadata('rank'));
$filter->setLowerBoundaryIncluded(1);
$filter->setUpperBoundaryIncluded(5);
$booleanQuery->addMustFilterClause($filter);

$filter = new TermClause(new FullTextMetadata('title'));
$filter->setTestedValue('title');
$booleanQuery->addMustFilterClause($filter);
            
$searchMessage->setQuery($searchQuery);
```

### `EuropaSearch` object

As already said sooner, It is the entry point for the host applications like Drupal. 
From it, the Europa search ingestion and search REST services are accessible.

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
   - For an ingestion request, if we use the `OpenEuropa\EuropaSearch\Messages\Index\IndexWebContent` from the example of
     the "[Message objects](#message-objects)" section:<br />
     ```php
     $response = $indexApp->sendMessage($webContentMessage);
     ```
 
     `$response` is an `OpenEuropa\EuropaSearch\Mssages\Index\IndexingResponse` object containing
     the indexed reference and a tracking id returned by the REST services.

   - For a search request, if we use the `OpenEuropa\EuropaSearch\Messages\Search\SearchMessage` from the example of
     the "[Message objects](#message-objects)" section:<br />
     ```php
     $response = $searchApp->sendMessage($searchMessage);
     ```
 
     `$response` is an `OpenEuropa\EuropaSearch\Mssages\Search\SearchResponse` object containing
     search results and some other data related to the current search like the total number of results.

## Architectural overview

### Architecture in layers

The library is organized into 3 layers with a specific scope:
- **Applications layer**: it is called by 3rd party systems like Drupal.<br />  
  It is in charge of:
  - Receiving the request messages to send the Europa Search REST services;
  - Validating the message content before continue the process.
- **Proxies layer**: it is in charge of 
  - Converting the received message into a request that will be sent to the REST services;
  - Routing the request to the right transporter service in order that it sends the request;
  - Returning the REST services responses to the Applications layer.
- **Transporters layer**: it is the layer that manages the requests to the REST services.
  
To have more information about these layers, please consult the [API documentation](#api-documentation).

## API Documentation

The Documentation is to be generated by your favorite phpDoc generator like [phpDocumentor](https://phpdoc.org).

It is recommended to have and consult the one for these packages:
- `OpenEuropa\EuropaSearch\Messages`;
- `OpenEuropa\EuropaSearch\Applications`;

And the one of the class `OpenEuropa\EuropaSearch\EuropaSearch`.<br />
They complete the information given in the [Library use](#library-use) section.


## Quality control

The automatic quality control is managed by the ["OpenEuropa code review"](https://github.com/openeuropa/code-review) component.
 
The component depends on [GrumPHP](https://github.com/phpro/grumphp) and based its controls on the Drupal coding convention.

Check the ["OpenEuropa code review" documentation](https://github.com/openeuropa/code-review/blob/master/README.md) for more.

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
