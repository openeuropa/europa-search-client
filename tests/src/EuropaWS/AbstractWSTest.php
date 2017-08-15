<?php
/**
 * @file
 * Contains EC\EuropaWS\Tests\AbstractWSTest.
 */

namespace EC\EuropaWS\Tests;

use EC\EuropaWS\Tests\Dummies\ClientContainerFactoryDummy;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractWSTest.
 *
 * Centralize all methods shared between test cases.
 *
 * @package EC\EuropaWS\Tests
 */
abstract class AbstractWSTest extends TestCase
{
    /**
     * Gets the ContainerBuilder used by unit tests.
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     *   The ContainerBuilder implementation
     */
    protected function getContainer()
    {
        return (new ClientContainerFactoryDummy())->getClientContainer();
    }
}
