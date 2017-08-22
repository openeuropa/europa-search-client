# EuropaWS package

## Introduction

This package proposes a generic mechanism for interfacing a system (consumer system) with any web services.

It divides a web service client into 3 layers:

A **Client layer** is called by the system that needs to send requests to the web service.

[Read more](02-client-layer.md)

A **Proxy layer** is in charge of:
  - Converting data coming from the application layer;
  - Calling the transportation layer for sending the request to the service;
  - Treating the service response and returning it to the application layer.
                                                                            
[Read more](03-proxy-layer.md)

A **Transportation layer** is directly in charge of the data exchanges with the web service.<br />
 The coverage of this layer is in progress. it is up to implementation to define its 
 layer based on the chosen library or framework (GuzzleHTTP, HTTPlug...) to manage it.<br /><br />
 This layer is not implemented yet.
 
From these layers, only messages from the client layer are used directly by the system. 
The rest of the implementation is called through an extension of the `EC\EuropaWS\ClientContainerFactory`.

[Read more](01-client-container-factory.md)

## Package API documentation

The API documentation is available [here](api/index.html).

## Why does it not have its own project?

The creation of this package has been initiated through the implementation of the "Europa Search" client.

The focus is on this client and this package is a bonus.
 
To be eligible for a separated project, it must provide:
- A better unit test coverage; so far, the main tests are provided by the "Europa Search" implementation.<br />
  Only the general mechanisms are covered by unit tests.
- A better coverage of the Transport layer.<br />
  Further investigation are necessary for being able to support any framework.
- A management of the messages with components that contain child components.<br />
  A first implementation has been made in the EuropaSearch package (see 'EC\EuropaSearch\Proxies\Search\SearchProxyController) 
  that requires some adjustments before being generic.  

This work is still <span style="color:red">in progress</span>.
