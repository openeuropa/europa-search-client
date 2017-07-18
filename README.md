oequest messages nor implementing SOAP interactions.
r Search Client Library

The Europa Search Client Library aims to hide Europa Search services complexity behind and 
easy-to-use client library so that users don't have to worry about building their own 
request messages nor implementing SOAP interactions.

Table of content:
=================
- [Architectural overview](#architectural-overview)
- [How to use the library](#use)
- [Testing](#testing)
- [Dependencies](#dependencies)

[Go to top](#table-of-content)

## Architectural overview

### Architecture in layers

The library is divided into 3 layers that are loosely coupled in order to reduce as much as impacts of possible changes in the REST API attached to Europa Search services:

- **Client layer**: it is accessed by systems that consume Europa Search REST services.<br />
It is controlled that the minimum client settings are present.<br />
It consists in 2 classes:
  - _IndexClient_ in charge of managing indexing requests from consumer systems.
  - _Search Client_ in charge of managing search query requests from consumer systems.<br /> 
- **Communication layer**: it makes the link between the client layer and the transmission layer; to 
prepare/convert data coming from the client layer into objects usable by the transmission layer.<br />
It also in charge to validate the format of the data passed by the client server is correct.<br />
It is materialized by classes implementing:
  - _EC\EuropaSearch\Index\Communication\ConverterInterface_ that treat data related to indexing requests.
  - _EC\EuropaSearch\Search\Communication\ConverterInterface_ that treat data related to search query requests.
- **Transmission layer**: It manages the connexions with Europa Search services and the transmission of data between the upper layer and the services.
It is materialized by classes implementing:
  - _EC\EuropaSearch\Index\Transmission\TransmitterInterface_ that manages connexions and transmissions for indexing requests.
  - _EC\EuropaSearch\Search\Transmission\TransmitterInterface_ that manages connexions and transmissions for search query requests.

[Go to top](#table-of-content)

### Exchanged data containers

#### Client layer

The client layer uses 4 types of objects:

1. **ServiceConfiguration**: it allows set connexion parameters that will be used by the transmission layer in order to instantiate connexion with the Europa Search services.
2. **IndexedDocument**: it contains data related to the indexing request for one document (web page or binary file)
3. **SearchQuery**: it contains data related to the search query request.
4. **SearchResults**: it contains data related to the results returned for a search query request.

#### Communication layer

This layer does not have specific object type as it is in charge to transform objects communicated by another layer into objects consumable by the other one.
So, it uses object types of the 2 other layers.

#### Transmission layer

The client layer uses 4 types of objects:

1. **ServiceConfiguration**: it is the one communicated by the client layer through the communication layer.
2. **IndexingRequest**: it contains data of an indexing request that it will format before sending it to the Europa Search "Indexing" service; aka "Ingestion API" services.
3. **SearchQueryRequest**: it contains data of a search query request that it will format before sending it to the Europa Search "Search" service; aka "Search API" services.
4. **SearchQueryRequest**: it contains data of the response of a search query request that it will format before sending it to the Europa Search "Search" service.

[Go to top](#table-of-content)

### Error management

`To do`

[Go to top](#table-of-content)

## How to use the library

`To do`

[Go to top](#table-of-content)

## Testing

`To do`

[Go to top](#table-of-content)

## Dependencies

`To do`

[Go to top](#table-of-content)

