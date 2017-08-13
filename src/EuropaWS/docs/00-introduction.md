# EuropaWS package

## Introduction

This package proposes a generic mechanism for interfacing a system with any web service.

It divides a web service client into 3 layers:

A **Client layer** called by the system that needs to send requests to the web service.<br />
[Read more](02-client-layer.md)

A **Proxy layer** is in charge of:
  - Converting data coming from the application layer;
  - Calling the transportation layer for sending the request to the service;
  - Treating the service response and returning it to the application layer.
                                                                            
[Read more](03-proxy-layer.md)

A **Transportation layer** that is directly in charge of the data exchanges with the web service.<br />
 The coverage of this layer is in progress. it is up to implementation to define its 
 layer based on the chosen library or framework (GuzzleHTTP, HTTPlug...) to manage it.
 
From these layers, only messages from the application layer are used directly by the system. 
The rest of the implementation is called through an extension of the `EC\EuropaWS\ClientContainerFactory`.<br />
[Read more](01-client-container-factory.md)

## Dependencies

The package mechanism depends on the following projects:
- [Symfony Validator Component](https://symfony.com/doc/current/components/validator.html): provides tools for 
the validation mechanism
- [Symfony DependencyInjection Component](https://symfony.com/doc/current/components/dependency_injection.html): provides tools for 
  reducing dependencies between the different layers.
- [Symfony Yaml Component](https://symfony.com/doc/current/components/yaml.html) and 
  [Symfony Config Component](https://symfony.com/doc/current/components/config.html): provide tools for managing dependency injections between
  the different layers from a YAML definition stored in files.

## Why does it not have its own project?

The creation of this package has initiated through the implementation of the "Europa Search" client.

The focus is on this client and this package is a bonus.
 
To be eligible for a separated project, it must provide:
- A better unit test coverage; so far, the main tests are provided by the "Europa Search" implementation.<br />
  Only the general mechanisms are covered by unit tests.
- A better coverage of the Transport layer.<br />
  Further investigation are necessary for being able to support any framework.

This work is still in progress.
