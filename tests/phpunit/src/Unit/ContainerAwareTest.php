<?php
namespace Mooti\Test\PHPUnit\Container\Unit;

use Mooti\Container\ContainerAware;
use Interop\Container\ContainerInterface;
use Mooti\Test\PHPUnit\Container\Unit\Fixture\TestClassNoContainerAware;
use Mooti\Test\PHPUnit\Container\Unit\Fixture\TestClassWithContainerAware;

class ContainerAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function setterAnGetterSucceeds()
    {
        $containerAware = $this->getMockForTrait(ContainerAware::class);
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $containerAware->setContainer($container);
        
        self::assertSame($container, $containerAware->getContainer());
    }

    /**
     * @test
     */
    public function getSucceeds()
    {
        $containerAware = $this->getMockForTrait(ContainerAware::class);
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $container->expects(self::once())
            ->method('get')
            ->with(self::equalTo('foo'))
            ->will(self::returnValue('bar'));

        $containerAware->setContainer($container);
        
        self::assertSame('bar', $containerAware->get('foo'));
    }

    /**
     * @test
     */
    public function createNewNoContainerAwareSucceeds()
    {
        $containerAware = $this->getMockForTrait(ContainerAware::class);

        self::assertInstanceOf(TestClassNoContainerAware::class, $containerAware->createNew(TestClassNoContainerAware::class));
    }

    /**
     * @test
     */
    public function createNewWithContainerAwareSucceeds()
    {
        $containerAware = $this->getMockForTrait(ContainerAware::class);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $containerAware->setContainer($container);

        $newObject = $containerAware->createNew(TestClassWithContainerAware::class);

        self::assertInstanceOf(TestClassWithContainerAware::class, $newObject);

        self::assertSame($container, $newObject->getContainer());
    }
}
