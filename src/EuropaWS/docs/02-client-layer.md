# Client layer

This layer is in charge of the interaction between the client and the consumer system.

It is built around messages, their components and the client.

It should not be called by the system but through the client container factory (More info [here](01-client-container-factory.md))

## Messages

They are container objects for messages transmitted by the system to the client.<br />

They are also responsible for declaring:
  - Validity constraints that will be checked by a triggered validation process on the message and its components.
  - A key that will be used by the proxy layer for retrieving the right converter class and converting the message and 
    its components (More info [here](03-proxy-layer.md)).
 
Depending on the interface they implement, the messages have different natures:
 - By implementing the `EC\EuropaWS\Messages\ValidatableMessageInterface`, they represent messages that are to be validated 
   before any further treatments.
 - By implementing the `EC\EuropaWS\IdentifiableMessageInterface`, they extend `ValidtableMessageInterface` by defining an identifier
   for the message content that is used by the consumer system and the web service in their communications.
 
These interfaces can be also used for managing messages returned from the web services and transmitted to the consumer system.    
 
## Components

Messages can be made of components; IE. attribute objects that represent a complex 
part of the message content.<br />
Like the messages, they are also responsible for declaring:
  - Validity constraints that will be checked by a triggered validation process on the message components.
  - A key that will be used by the proxy layer for retrieving the right converter class and convert the message 
    components (More info [here](03-proxy-layer.md)).
    
They implement `EC\EuropaWS\Messages\Components\ComponentInteface`.
 
# Client

It is an object responsible for receiving the messages from the consumer system and sending them to the proxy layer.

It must implement `EC\EuropaWS\Clients\ClientInterface`. 

The package supplies a default implementation that is used already in the EuropaSearch implementation: 
`EC\EuropaWS\Clients\DefaultClient`.<br />
 This implementation is not complete yet as it misses the integration with the Transport layer (<span style="color:orange">coming soon</span>).

It should not be called directly by the system that uses the client but through the mechanism based on 
`EC\EuropaWS\ClientContainerFactory` and EC\EuropaWS\Clients\ClientProvider` (More info `
[here](01-client-container-factory.md)).
