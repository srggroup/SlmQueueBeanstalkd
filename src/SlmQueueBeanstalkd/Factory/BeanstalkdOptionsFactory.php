<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;

class BeanstalkdOptionsFactory implements FactoryInterface {


	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): BeanstalkdOptions {
		$config = $container->get('config');

		return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
	}


}
