<?php

/**
 * @file
 * Contains EC\EuropaWS\Tests\ClientContainerTest.
 */

namespace EC\EuropaWS\Tests;

use EC\EuropaWS\Tests\Dummies\ClientContainerFactoryDummy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validator\RecursiveValidator;

/**
 * Class ClientContainerTest.
 *
 * Tests the mechanism retrieving the ClientContainer.
 *
 * @package EC\EuropaWS\Test
 */
class ClientContainerTest extends TestCase
{
    /**
     * Tests that a ClientContainer gets correctly the client container.
     */
    public function testClientContainerInstantiation()
    {
        $factory = new ClientContainerFactoryDummy();
        $clientContainer = $factory->getClientContainer();
        $clientContainer2 = $factory->getClientContainer();

        $this->assertInstanceOf(ContainerBuilder::class, $clientContainer, 'The returned container is not a ClientContainerDummy instance.');

        $this->assertEquals($clientContainer, $clientContainer2, '"getClientContainer()" does not return a singleton as expected.');
    }

    /**
     * Tests if the validator returned client container is the expected class.
     */
    public function testDefaultValidatorClass()
    {
        $factory = new ClientContainerFactoryDummy();
        $validator = $factory->getDefaultValidator();

        $this->assertInstanceOf(RecursiveValidator::class, $validator, 'The returned validator is not a RecursiveValidator instance.');
    }
}
