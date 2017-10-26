# EuropaSearch package

## Introduction

This package is actual the Europa Search client implementation.

It is based on the EuropaWS package and organizes it structure on the same pattern; I.E. 3 layers and a client 
container factory (More info [here](../../EuropaWS/docs/00-introduction.md)).

It proposes already 2 service clients:
- **"IndexingClient"** that allows sending indexing request to the Ingestion API REST service of Europa Search.<br />
  So far, **it only supports web content** indexing requests but the support of files indexing is planned.
- **"SearchClient"** that allows sending search request to the SearchAPI REST service of Europa Search.<br />

Both are callable through the client container factory of the Europa Search: `EC\EuropaSearch\EuropaSearch` 
(For more information, see the [API documentation](api/classes/EC.EuropaSearch.EuropaSearch.html)).

## Configuration

### Web service configuration

`TO DO` when the transportation layer will be implemented.
 
### Client configuration
 
The client configuration is defined in the `service.yml` [file](../config/service.yml) of the repository. 
This file defines the different dependencies between the different layer (client, proxy and transport).
The only lines to modify in for your project needs, is the `parameters` area that contains the default web service settings.

For each client services, it defines:
- The transporter implementation the client uses (see the line `transporter`).<br />
  It requires the web service settings that are currently set in the YML file.<br /><br />
  <span style="color:orange;">Note</span>: The settings location will change with the transport layer implementation.<br /><br />
- The message converter classes the proxy layer will use to convert the message (see the lines `europaSearch.messageProxy.*`).<br />
  The service key like `europaSearch.messageProxy.indexing.webContent` is the converter identifier set in the message object 
  (`getConverterIdentifier()`).
- The component converter classes the proxy layer will use to convert the component (see the lines `europaSearch.componentProxy.*`).<br />
  The service key like `europaSearch.componentProxy.metadata.boolean` is the converter identifier set in the component object 
  (`getConverterIdentifier()`). 
- The proxy controller implementation the client uses to process the conversions of messages and components 
  (see the lines `proxyController.*`).<br />
  It receives as parameters the list of converter classes with their key (see the lines `calls` or `proxyController.*`), and the 
  web service configuration as argument constructor.<br /><br />
  <span style="color:orange;">Note</span>: The argument constructor will change with the transport layer implementation.
- The client layer via its ClientInterface implementation (see the lines `client.*)`that needs to have a validator, 
  a proxy controller, a transporter and the web service configuration to run.<br />
  It is the class that is called when you use the `getIndexingWebContentClient()` and `getSearchingClient()` methods.
  <br /><br />
  <span style="color:orange;">Note</span>: The argument constructor will change with the transport layer implementation.

## IndexingClient

The first client service is "IndexingClient" that allows sending indexing requests to the Ingestion API REST service of 
Europa Search.

Before calling it, the consumer system must defined indexing messages and its components through the following objects:

### Message objects

So far, there are 2 types of messages.

#### IndexingWebContent

This message object allows defining the message representing an **indexing request for a web content**. You can learn more about 
its structure, please consult the [API documentation](api/classes/EC.EuropaSearch.Messages.Index.IndexingWebContent.html).

#### FileWebContent

This message object allows defining the message representing an **indexing request for a file**, when this part of the client will 
be implemented.

### Component objects

#### Indexing Services: the Metadata components.

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

For more information about the related classes, read the [API documentation](api/namespaces/EC.EuropaSearch.Messages.DocumentMetadata.html).

As the library supports the Dynamic schema of Europa Search, the metadata names can be those already used in the consumer system.<br />
The client service via its proxy layer ensures that the names are formatted correctly before sending the request; I.E. 
the names are prefixed with the sequence declaring its type<br />
For instance, a string metadata name in the system is `blabla`, it will become `esST_blabla` when it is sent.

### Service use

To use the indexing client, you just have to define your message with the objects presented in the previous section and 
called the client layer as follow:

```php
...
$factory = new EruropaSearch();
$response = $factory->getIndexingWebContentClient()->sendMessage($indexingMessage);
...
```

## SearchingClient

The second client service is "SearchingClient" that allows sending search requests to the Search API REST service of 
Europa Search.

Before calling it, the consumer system must defined indexing messages and its components through the following objects:

### Message objects

So far, there is 1 type of messages.

#### SearchMessage

This message object allows defining the message representing a **search request**. You can learn more about 
its structure, please consult the [API documentation](api/classes/EC.EuropaSearch.Messages.Search.SearchMessage.html).

### Component objects

#### Search Services: the filters and queries components.

Each sent search request contains a query used for filtering the search results. This query is made of the different filter types 
that form the components of the search messages.

There are 2 main types composed themselves of sub-types:
- The simple filters that defines basic filter criteria:
   * exists (`FieldExists`): Defines a filter on the existence of a specific metadata set in a indexed document;
   * range (`Range`): Defines a filter on a specific metadata based on a range of dates or numbers;
   * term (`Term`): Defines a filter on a specific metadata based on a defined value;
   * terms (`Terms`): Defines a filter on a specific metadata based on a list of values;
- The combined filters that defines filter criteria based on a series of filters (simple or combined) targeting different metadata:
   * aggregated (`AggregatedFilters`): Defines a group of filters (combined or simple) that will be used in one of the next 
     combined filter types;
   * Bool (`BooleanQuery`): Defines a filter query based on 3 aggregated filter as foreseen by the Europa Search query API:
     - 'must': Results MUST fulfill. it can be used to build "AND" equivalent where clause.
     - 'must_not': Results MUST NOT fulfill. it can be used to build "NOT" equivalent where clause.
     - 'should': Results should fulfill. it can be used to build "OR" equivalent where clause.
   * Boosting (`BoostingQuery`): Defines a filter query based on 2 aggregated filter as foreseen by the Europa Search query API:
     - 'positive': Items fulfilling query's criteria will be better placed in the result list.<br />
       It can contain `StringMetadata`, `URLMetadata`, `IntegerMetadata` or `FloatMetadata` only.
     - 'negative': Items fulfilling query's criteria will be less well placed in the result list.<br />
       It can contain `StringMetadata`, `URLMetadata`, `IntegerMetadata` or `FloatMetadata` only.

For more information about the related classes, read the [API documentation](api/namespaces/EC.EuropaSearch.Messages.Search.Filters.html).

As the library supports the Dynamic schema of Europa Search, the metadata names implies in the queries can be those already used in 
the consumer system.<br />
The client service via its proxy layer ensures that the names are formatted correctly before sending the request; I.E. 
the names are prefixed with the sequence declaring its type<br />
For instance, a string metadata name in the system is `blabla`, it will become `esST_blabla` when it is sent.

### Service use

To use the searching client, you just have to define your message with the objects presented in the previous section and 
called the client layer as follow:

```php
...
$factory = new EuropaSearch();
$response = $factory->getSearchingClient()->sendMessage($indexingMessage);
...
```

