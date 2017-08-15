<?php

/**
 * @file
 * Contains EC\EuropaWS\Clients\DefaultClient.
 */

namespace EC\EuropaWS\Clients;

use EC\EuropaWS\Exceptions\ValidationException;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Proxies\ProxyProvider;
use EC\EuropaWS\Transporters\TransporterInterface;
use Symfony\Component\Validator\ValidatorBuilder;
use EC\EuropaWS\Common\WSConfigurationInterface;

/**
 * Class DefaultClient.
 *
 * Default implementation of the ClientInterface.
 *
 * @package EC\EuropaWS\Clients
 */
class DefaultClient implements ClientInterface
{
    /**
     * The validator to use with messages
     *
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * The proxy to use for convert the sent message.
     *
     * @var \EC\EuropaWS\Proxies\ProxyProvider
     */
    protected $proxy;

    /**
     * The transporter to eventually send the request with the message.
     *
     * @var \EC\EuropaWS\Transporters\TransporterInterface
     */
    protected $transporter;

    /**
     * The web service configuration.
     *
     * @var \EC\EuropaWS\Common\WSConfigurationInterface
     */
    protected $WSConfiguration;

    /**
     * DefaultClient constructor.
     *
     * @param \Symfony\Component\Validator\ValidatorBuilder  $validator
     * @param \EC\EuropaWS\Proxies\ProxyProvider             $proxy
     * @param \EC\EuropaWS\Transporters\TransporterInterface $transporter
     * @param \EC\EuropaWS\Common\WSConfigurationInterface   $WSConfiguration
     */
    public function __construct(ValidatorBuilder $validator, ProxyProvider $proxy, TransporterInterface $transporter, WSConfigurationInterface $WSConfiguration)
    {
        $this->validator = $validator->getValidator();
        $this->proxy = $proxy;
        $this->transporter = $transporter;
        $this->WSConfiguration = $WSConfiguration;
    }


    /**
     * {@inheritDoc}
     */
    public function sendMessage(ValidatableMessageInterface $message)
    {
            $this->validateMessage($message);
            $convertedComponents = $this->proxy->convertComponents($message->getComponents());
            $request = $this->proxy->convertMessageWithComponents($message, $convertedComponents);
            $this->transporter->setWSConfiguration($this->WSConfiguration);
            $response = $this->transporter->send($request, $this->WSConfiguration);

            return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function validateMessage(ValidatableMessageInterface $message)
    {
        $violations = $this->validator->validate($message);
        if (!empty($violations) && ($violations->count() != 0)) {
            $errorMessages = array();
            foreach ($violations as $violation) {
                $errorMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            $validException =  new ValidationException('The message submitted is invalid', 282);
            $validException->setValidationErrors($errorMessages);

            throw $validException;
        }
    }
}
