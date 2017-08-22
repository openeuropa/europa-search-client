# "ClientContainerfactory"

It is the central point of the whole mechanism because it is responsible for:
- The configuration loading;
- The dependency injection management;
- Providing a default ValidatorBuilder;
- Interacting with the consumer system.

## Responsibilities

### Configuration loading

It is in charge of loading the client services (Proxies, Transporters) configuration that is located in
a 'services.xml' YAML file.
 
Without an extension of this class, this repository is located in the same repository 
as the factory class, but if the client implementation extends the class, it can also 
set a more appropriate location like shown in this example:

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

The proxy layer uses it in order to link the Message and their components to their converter classes and to call
the transport layer.


### A default ValidatorBuilder

By default, the factory supplies a 
`Symfony\Component\Validator\ValidatorBuilder` object (see 
[Symfony Validator component documentation](https://symfony.com/doc/current/validation.html)); instantiated itself by the 
`EC\EuropaWS\Common\DefaultValidatorBuilder` object.

It declares that validation constraints can be retrieved from a `getConstraints()` 
method declared in the object in order to validate them.

The derived Validator object can be easily be called as this example shows it:
```php
...
$factory = new ClientContainerFactory();
$validator = $container->getDefaultValidator();
...
```

### Interacting with the consumer system.
 
The client layer should not be called directly from the calling system but from here.

The system should only deal with message (and component) object and call the factory in order to supply it to 
the client layer, as shown in this example:

```php
...
// Instantiate the message object implemented 'IdentifiableMessageInterface' or 'MessageInterface'
$data = new DummyMessage();

... $data definition process ... 

$factory = new EuropaSearchDummy();

// Gets the client from the factory, here the client library implements a convenient method to retrieve 
// the right client as shown below.
$client = $factory->getIndexingWebContentClient();

// Sending the message and retrieving the web service response.
$response = $client->sendMessage($data);
...
```





