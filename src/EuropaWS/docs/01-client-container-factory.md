# "ClientContainerfactory"

It is the central point of the whole mechanism because it is responsible for:
- The configuration loading
- The dependency injection management
- Providing a default validator

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

The different service providers (I.E. `EC\EuropaWS\Clients\ClientProvider`
and `EC\EuropaWS\Proxies\ProxyProvider`) exploit it in 
order to map the Message and their component to their proxy class in charge to 
transform them and communicate them to the transport layer of the client that will send the
request to web service.

### Provide a default validator

The factory proposes a convenient method that supplies a 
`Symfony\Component\Validator\Validator\RecursiveValidator` object; instantiated itself by the 
`EC\EuropaWS\Common\ValidatorProvider` object.

It can be used to validate any object that exposes a `getConstraints()` method.

## How to extend it?

The class could be used "as is" in any client project, but it is recommend to extend it in order 
to expose convenient methods for the client library developers.

An example of these methods is the definition of shortcut to the `ClientInterface` implementation 
of the library; I.E. replace this:

```php
$factory = new ClientContainerFactory();
$provider = new ClientProvider($factory);

$client = $provider->getClient('client.dummy');
```

By:

```php
$factory = new ClientContainerFactory();
$client = $factory->getDummyClient();

```





