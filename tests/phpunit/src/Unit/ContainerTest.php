<?php
namespace Mooti\Test\PHPUnit\Container\Unit;

use Mooti\Container\Container;
use Mooti\Container\ServiceProvider\ServiceProviderInterface;
use Mooti\Test\PHPUnit\Container\Unit\Fixture\TestClassWithContainerAware;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getSucceedsWithObject()
    {
        $id   = 'foobar';
        $item = new \stdClass();

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        
        $container->set($id, $item);
        self::assertSame($item, $container->get($id));
    }

    /**
     * @test
     */
    public function getSucceedsWithVariableReturnedByCallable()
    {
        $id   = 'foo';
        $item = function(){return 'bar';};

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $container->set($id, $item);
        self::assertSame('bar', $container->get($id));
    }

    /**
     * @test
     */
    public function getSucceedsWithObjectReturnedByCallable()
    {
        $id     = 'foobar';
        $object = new \stdClass();
        $item   = function() use ($object) {return $object;};

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        
        $container->set($id, $item);
        self::assertSame($object, $container->get($id));
    }

    /**
     * @test
     */
    public function getSucceedsWithContainerAwareObjectReturnedByCallable()
    {
        $id     = 'foobar';
        $object = new TestClassWithContainerAware;
        $item   = function() use ($object) {return $object;};

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $container->set($id, $item);
        self::assertSame($object, $container->get($id));
    }

    /**
     * @test
     * @expectedException Interop\Container\Exception\NotFoundException
     */
    public function getThrowsItemNotFoundException()
    {
        $id = 'foobar';

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $container->get($id);
    }

    /**
     * @test
     */
    public function hasSucceeds()
    {
        $id   = 'foo';
        $item = 'bar';

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $container->set($id, $item);
        self::assertSame(true, $container->has($id));
    }

    /**
     * @test
     */
    public function registerServicesSucceeds()
    {
        $services = [
            'foo' => new \stdClass(),
            'bar' => new \stdClass()
        ];

        $serviceProvider = $this->getMockBuilder(ServiceProviderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceProvider->expects(self::once())
            ->method('getServices')
            ->will(self::returnValue($services));

        $container = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(['has', 'set'])
            ->getMock();

        $container->expects(self::exactly(2))
            ->method('has')
            ->withConsecutive([self::equalTo('foo')],[self::equalTo('bar')])
            ->will(self::onConsecutiveCalls(true, false));

        $container->expects(self::once())
            ->method('set')
            ->with(self::equalTo('bar'), self::equalTo($services['bar']));

        $container->registerServices($serviceProvider);
    }
}
