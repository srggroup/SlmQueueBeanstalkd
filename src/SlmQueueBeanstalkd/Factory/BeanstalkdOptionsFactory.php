<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
use Laminas\ServiceManager\Factory\FactoryInterface as FactoryInterface;

/**
 * Class BeanstalkdOptionsFactory
 *
 * @package   SlmQueueBeanstalkd\Factory
 * @author    SRG Group <dev@srg.hu>
 * @copyright 2019 SRG Group Kft.
 */
class BeanstalkdOptionsFactory implements FactoryInterface {

	/**
	 * @param ContainerInterface $container
	 * @param string             $requestedName
	 * @param array|null         $options
	 *
	 * @return object|BeanstalkdOptions
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$config = $container->get('config');
		return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
	}

}
