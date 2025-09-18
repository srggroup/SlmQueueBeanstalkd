<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;

class BeanstalkdOptionsFactory implements FactoryInterface {


	/**
	 * @param string             $requestedName
	 * @param array|null         $options
	 * @return object|BeanstalkdOptions
	 */
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null) {
		$config = $container->get('config');

		return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
	}


}
