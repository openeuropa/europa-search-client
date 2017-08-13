<?php
/**
 * @file
 * Contains EC\EuropaWS\ClientContainerFactory.
 */

namespace EC\EuropaWS;

use EC\EuropaWS\Exceptions\ClientInstantiationException;
use EC\EuropaWS\Common\ValidatorProvider;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\DirectoryLoader;
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
     * Gets the default validator embedded in the framework.
     *
     * @return mixed
     */
    public function getDefaultValidator()
    {
        return $this->getClientContainer()->get('services.validator.default')->getValidator();
    }

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
        $this->configRepoPath = __DIR__;
        if (is_null(static::$container)) {
            $this->buildClientContainer();
        }
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
            $fileLocator = new FileLocator($this->configRepoPath);
            $loader = new DirectoryLoader($container, $fileLocator);
            $loader->setResolver(new LoaderResolver(array(
                new YamlFileLoader($container, $fileLocator),
                $loader,
            )));
            $loader->load('client_services');

            //Add the default validator implementation
            $container->register('services.validator.default', ValidatorProvider::class);
        } catch (Exception $e) {
            throw new ClientInstantiationException('The client container instantiation failed.', $e);
        }

        static::$container = $container;
    }
}
