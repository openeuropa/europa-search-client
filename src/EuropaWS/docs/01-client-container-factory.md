# "ClientContainerfactory"

It is the central point of the whole mechanism because it is responsible for:
- The configuration loading
- The dependency injection management
- Providing a default ValidatorBuilder

## Responsibilities

### Configuration loading

It is in charge of loading the client services configuration that it located in
one or several YAML files stored in the "client_services" repository.
 
Without an extension of this class, this repository is located in the same repository 
as the factory class, but if the client implementation extends the class, it can also 
set another  more appropriate location like shown in this example:

```php
class ClientContainerFactoryDummy extends ClientContainerFactory
{

    /**
     * ClientContainer constructor.
     */
    public function __construct()
    {
        $this->configRepoPath = '/somwhereelse';
    }
}
```

### Dependency injection management

It plays the dependency injection container based on 
[Symfony DependencyInjection Component](https://symfony.com/doc/current/components/dependency_injection.html)
whose only responsibility is to build and provide ready-to-use, fully configured services.

The proxy provider (I.E. `EC\EuropaWS\Proxies\DefaultProxyController`) exploits it in 
order to map the Message and their component to their proxy class in charge to 
transform them and communicate them to the transport layer of the client that will send the
request to web service.

### Provide a default ValidatorBuilder

By default, the factory supplies a 
`Symfony\Component\Validator\ValidatorBuilder` object; instantiated itself by the 
`EC\EuropaWS\Common\DefaultValidatorBuilder` object.

It declares that validation constraints can be retrieved from a `getConstraints()` 
method declared in the object in order to validate them.

## How to extend it?

TODO

```php
TODO
```

By:

```php
TODO

```





