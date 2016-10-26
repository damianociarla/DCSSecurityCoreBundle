<?php

namespace DCS\Security\CoreBundle\Tests\DependencyInjection;

use DCS\Security\CoreBundle\DependencyInjection\DCSSecurityCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DCSSecurityCoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();

        $mock = $this->getMockBuilder(DCSSecurityCoreExtension::class)
            ->setMethods(['processConfiguration'])
            ->getMock();

        $config = [
            'dcs_security_core' => [
                'provider_key' => 'configuration stuff',
            ]
        ];

        $mock->load($config, $container);

        $this->assertTrue($container->hasParameter('dcs_security.core.provider_key'));
        $this->assertEquals('configuration stuff', $container->getParameter('dcs_security.core.provider_key'));
    }
}
