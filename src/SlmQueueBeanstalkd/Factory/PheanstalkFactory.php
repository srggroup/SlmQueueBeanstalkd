<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Pheanstalk\Connection;
use Pheanstalk\Pheanstalk;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;

/**
 * PheanstalkFactory
 */
class PheanstalkFactory implements FactoryInterface {

	/**
	 * @param ContainerInterface $container
	 * @param                    $requestedName
	 * @param array|null         $options
	 *
	 * @return object|Pheanstalk
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		/** @var $beanstalkdOptions BeanstalkdOptions */
		$beanstalkdOptions = $container->get('SlmQueueBeanstalkd\Options\BeanstalkdOptions');
		$connectionOptions = $beanstalkdOptions->getConnection();

		return Pheanstalk::create(
			$connectionOptions->getHost(),
			$connectionOptions->getPort(),
			$connectionOptions->getTimeout()
		);
	}


}
