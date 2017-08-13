# Proxy Layer

This layer is in charge of:
- Converting messages coming from the application layer;
- Calling the transportation layer for sending the request to the service;
- Treating the service response and returning it to the application layer.

## Responsibilities

### Message conversion

The message conversion is made by an implementation of 
`EC\EuropaWS\Proxies\ProxyInterface`.
If message to convert contains components, the implementation can call 
implementations of `EC\EuropaWS\Proxies\ComponentProxyInterface` in order to 
convert the component and use it in the message treatment.

### Call the transportation layer

The call occurs in the `communicateRequest()` method of the implementation of 
`ProxyInterface`.
It is in this method that the final call to the transporter layer is done 
regardless of the chosen implementation.

### Treating the service response

It still is in the `communicateRequest()` method the treatment occurs.

When the service responses to the request, the method process catches it and 
treat to return it in `MessageInterface` object it communicates to the [client layer](02-client-layer.md)

### How does it works?

TODO Take examples from the implementation of the Europa Search client  
