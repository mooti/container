# Mooti Container

[![Build Status](https://travis-ci.org/mooti/container.svg?branch=master)](https://travis-ci.org/mooti/container)
[![Coverage Status](https://coveralls.io/repos/github/mooti/container/badge.svg?branch=master)](https://coveralls.io/github/mooti/container?branch=master)
[![Latest Stable Version](https://poser.pugx.org/mooti/container/v/stable)](https://packagist.org/packages/mooti/container)
[![Total Downloads](https://poser.pugx.org/mooti/container/downloads)](https://packagist.org/packages/mooti/container)
[![Latest Unstable Version](https://poser.pugx.org/mooti/container/v/unstable)](https://packagist.org/packages/mooti/container)
[![License](https://poser.pugx.org/mooti/container/license)](https://packagist.org/packages/mooti/container)

A simple service container written in php

### Installation

You can install this through packagist

```
$ composer require mooti/container
```

### Run the tests

If you would like to run the tests. Use the following:

```
$ ./vendor/bin/phpunit
```

### Usage

Create the container and add a service

```
use Mooti\Container\Container;

$container = new Container();

$container->set('logger', function () { return new Logger();});

//returns a new instance of Logger. Subsequent calls return the same object
$logger = $container->get('logger');
```

You also define multiple services by implementing `ServiceProviderInterface` and then implementing it's `getServices` method to return an associative array. If the element is a callable function it will be called and the result will be returned.


```
use Mooti\Container\ContainerAware;

class ServiceProvider implements ServiceProviderInterface
{
    public function getServices()
    {
        return [
            'logger'  => function () { return new Logger();},
            'message' => 'Hello World'},
        ];
    }
}

```

To us the container you will need to use the `ContainerAware` trait in you class. You will then be able to use the `get` method to get any items from the container

```
use Mooti\Container\ContainerAware;

class App
{
    use ContainerAware;

    public function run()
    {
    	$config  = $this->get('config');
    	$message = $this->get('message');

    	$logger->alert($message);
    }
}
```

So, putting it all together you will get something like:


```
use Mooti\Container\Container;
use Mooti\Container\ContainerAware;
use Mooti\Container\ServiceProvider\ServiceProviderInterface;

class App
{
    use ContainerAware;

    public function run()
    {
    	$config  = $this->get('config');
    	$message = $this->get('message');

    	$logger->alert($message);
    }
}

class ServiceProvider implements ServiceProviderInterface
{
    public function getServices()
    {
        return [
            'logger'  => function () { return new Logger();},
            'message' => 'Hello World'},
        ];
    }
}

$container       = new Container();
$serviceProvider = new ServiceProvider::class;
$app             = new App();

$container->registerServices($serviceProvider);
$app->setContainer($container);
$app->run();
```

This repository is not intendedd for creating mocks for testing. In order to do that, look at [mooti/factory](https://github.com/mooti/factory)