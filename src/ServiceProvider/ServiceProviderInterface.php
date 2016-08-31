<?php

namespace Mooti\Container\ServiceProvider;

interface ServiceProviderInterface
{
	/**
     * Get the details of the services we are providing     
     *
     * @return array Services in the format ['serviceId' => initFunction():Object ]
     */
    public function getServices();
}