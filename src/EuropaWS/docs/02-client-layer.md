# Client layer

This layer is in charge of the interaction between the client and the system that uses it.

It is built around:
- **Messages**: Container objects for messages transmitted by the system to the client.<br />
 They are also responsible to validate the message content and to declare which 
 conversion mechanism to use in the proxy layer (More info [here](03-proxy-layer.md)).<br />
 They implement the `EC\EuropaWS\Messages\MessageInterface` or if they must convey a message with 
 an identifier used by the service, they can implement `EC\EuropaWS\IdentifiableMessageInterface`. 
 
- **Components**: Messages can be made of components; IE. attribute objects that represent a complex 
part of the message content.<br />
Like the messages, also responsible to validate the message content and to declare which conversion mechanism 
to use in the proxy layer (More info [here](03-proxy-layer.md)).
They implement the `EC\EuropaWS\Messages\Components\ComponentInteface`.
 
- **Client**: Object responsible receiving the messages from the system and sending to the proxy layer.<br />
It should not be called directly by the system that uses the client but through the mechanism based on 
`EC\EuropaWS\ClientContainerFactory` and EC\EuropaWS\Clients\ClientProvider` (More info `
[here](01-client-container-factory.md)).<br /><br />
Example:

```php
...
// Instantiation of an extension of "ClientContainerFactory" in the library 
// using the package
$factory = new ExtensionContainerFactory();

// In order to get the "client" object", we use a convenient shortcut exposed
// in the factory extension.
$client = $factory->getDummyClient();

$response = $client->sendMessage($message);
...
```