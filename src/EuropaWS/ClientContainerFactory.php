<?php
/**
 * @file
 * Contains EC\EuropaWS\ClientContainerFactory.
 */

namespace EC\EuropaWS;

use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Common\DefaultValidatorBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ClientContainerFactory.
 *
 * It instaantiates the client container.
 *
 * Extending this class allows objects to load their own YML configuration file.
 *
 * @package EC\EuropaWS
 */
class ClientContainerFactory
{

    /**
     * Client container.
     *
     * @var \EC\EuropaWS\ClientContainer
     */
    protected static $container;

    /**
     * Path of the repository path whith services configuration file.
     *
     * @var string
     */
    protected $configRepoPath;

    /**
     * Gest the client container with the services definitions.
     *
     * @return ContainerBuilder
     *   The client container.
     */
    public function getClientContainer()
    {
        if (is_null(static::$container)) {
            $this->buildClientContainer();
        }

        return static::$container;
    }

    /**
     * ClientContainer constructor.
     */
    public function __construct()
    {
        $this->configRepoPath = __DIR__.'/config';
    }

    /**
     * Builds the client container.
     *
     * @throws ClientInstantiationException
     *   It is catch when the YML file are not found or malformed.
     */
    protected function buildClientContainer()
    {

        $container = new ContainerBuilder();
        try {
            //Add the default validator implementation
            $container->register('services.validator.default', DefaultValidatorBuilder::class);

            $loader = new YamlFileLoader($container, new FileLocator($this->configRepoPath));
            $loader->load('services.yml');

            static::$container = $container;
        } catch (Exception $e) {
            throw new ClientInstantiationException('The client container instantiation failed.', $e);
        }
    }
}
