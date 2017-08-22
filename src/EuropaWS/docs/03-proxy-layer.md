# Proxy Layer

This layer is in charge of:
- Converting messages coming from the application layer;
- Calling the transportation layer for sending the request to the service;
- Treating the service response and returning it to the application layer.

It should never be called directly by the consumer system, but through the client layer.

## Responsibilities

### Message conversion

The message conversion is made by an implementation of 
`EC\EuropaWS\Proxies\ProxyInterface`.

It identifies the converter class to use for a specific message thanks to its converter identifier 
(see `getConverterIdentifier()`) and the entries in its internal registry.<br />
A converter class implements `EC\EuropaWS\Proxies\MessageConverterInterface` for the messages and
`EC\EuropaWS\Proxies\ComponentConverterInterface` for the components.<br />
The internal registry is defined from the client container (see `EC\EuropaWS\ClientContainerFactory`) and lists
the relationships between the messages, the components and their converter.

It calls this converter to execute the message transformation.

It does the same for the message components. To identify them is retrieve them from the message through 
the `getComponents()` method and apply the same process as for the message on all components.

A converter class implements `EC\EuropaWS\Proxies\MessageConverterInterface` for the messages and
`EC\EuropaWS\Proxies\ComponentConverterInterface` for the components.

### Call the transportation layer

`TO DO` when the transportation layer will be implemented.

### How does it works?

The `DefaultClient::sendMessage()` [method](../Clients/DefaultClient.php) illustrates how the logic works a 
library based on this package.
