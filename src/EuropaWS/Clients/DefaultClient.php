<?php

/**
 * @file
 * Contains EC\EuropaWS\Clients\DefaultClient.
 */

namespace EC\EuropaWS\Clients;

use EC\EuropaWS\Exceptions\ValidationException;
use EC\EuropaWS\Messages\ValidatableMessageInterface;
use EC\EuropaWS\Proxies\BasicProxyController;
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
     * @var \EC\EuropaWS\Proxies\BasicProxyController
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
     * @param \EC\EuropaWS\Proxies\BasicProxyController      $proxy
     * @param \EC\EuropaWS\Transporters\TransporterInterface $transporter
     */
    public function __construct(ValidatorBuilder $validator, BasicProxyController $proxy, TransporterInterface $transporter)
    {

        $this->validator = $validator->getValidator();
        $this->proxy = $proxy;
        $this->transporter = $transporter;
    }

    /**
     * {@inheritDoc}
     */
    public function setWSConfiguration(WSConfigurationInterface $configuration)
    {

        $this->WSConfiguration = $configuration;
        $this->proxy->setWSConfiguration($configuration);
        $this->transporter->setWSConfiguration($configuration);
    }

    /**
     * {@inheritDoc}
     */
    public function sendMessage(ValidatableMessageInterface $message)
    {

        $this->validateMessage($message);

        $convertedComponents = $this->proxy->convertComponents($message->getComponents());
        $request = $this->proxy->convertMessageWithComponents($message, $convertedComponents);

        $response = $this->proxy->sendRequest($request, $this->transporter);

        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function validateMessage(ValidatableMessageInterface $message)
    {

        $violations = $this->validator->validate($message);
        if (!empty($violations) && ($violations->count() != 0)) {
            $errorMessages = [];
            foreach ($violations as $violation) {
                $errorMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            $validException =  new ValidationException('The message submitted is invalid');
            $validException->setValidationErrors($errorMessages);

            throw $validException;
        }
    }
}
