<?php
/*
* Main xizlr trait
*
* Use this in order to use the container
*
* @author Ken Lalobo
*
*/
namespace Mooti\Container;

use Mooti\Factory\Factory;
use Interop\Container\ContainerInterface;

trait ContainerAware
{
    use Factory;

    /**
     * @var ContainerInterface $container
     */
    protected $container;

    /**
     * @return ContainerInterface The current container being used
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container The container
     */
    public function setContainer(ContainerInterface $container)
    {
         $this->container = $container;
    }

    /**
     * Create a new instance of a given class
     *
     * @param string $className The class to create
     *
     * @return object The new class
     */
    public function createNew($className)
    {
        $constructArguments = func_get_args();
        $object = $this->_createNew( ...$constructArguments);
        
        $traits = class_uses($object);

        if (isset($traits[ContainerAware::class]) == true) {
            $object->setContainer($this->container);
        }

        return $object;
    }

    /**
     * Get an item from from the container
     *
     * @param string $id The Id of the item
     *
     * @return mixed The item
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
}
