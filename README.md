# Europa Search Client Library

The Europa Search Client Library aims to hide Europa Search services complexity behind a 
easy-to-use client library so that users don't have to worry about building their own 
request messages nor implementing REST interactions.

Table of content:
=================
- [Architectural overview](#architectural-overview)
- [How to use the library](#use)
- [Testing](#testing)
- [Dependencies](#dependencies)

[Go to top](#table-of-content)

## Architectural overview

### Architecture in layers

The library is based into 2 main packages:
- **EuropaWS** that gathers the generic mechanisms used in the Europa Search client; [read more](src/EuropaWS/docs/00-introduction.md);
- **EuropaSearch** that implements the client itself [read more](src/EuropaSearch/docs/00-introduction.md).

The EuropaWS package organizes the generic mechanism into 3 layers with a specific scope:
- **Client layer**: it is in charge of 
  - Receiving the request messages to send the Europa Search REST services;
  - Validating the message content before continue the process.
- **Proxy layer**: it is in charge of 
  - Converting the receive message into a request that will be sent to the REST services;
  - Routing the request to the right transporter service in order that it sent the request;
  - Returning the REST services responses to the client layer.
- **Transportation layer**: it is is the layer that manages the request sending to the REST services.
  
To have more information about these layers, pleas consult the [package documentation](src/EuropaWS/docs/00-introduction.md).

The EuropaSearch package specifies the EuropaWS mechanism to Europa Search client requirements.

## How to use the library

`TO DO`

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