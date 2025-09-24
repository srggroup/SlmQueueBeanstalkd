<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Pheanstalk\Pheanstalk;
use Pheanstalk\Values\Timeout;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;

class PheanstalkFactory implements FactoryInterface {


	/**
	 * @param string $requestedName
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): Pheanstalk {
		/** @var BeanstalkdOptions $beanstalkdOptions */
		$beanstalkdOptions = $container->get(BeanstalkdOptions::class);
		$connectionOptions = $beanstalkdOptions->getConnection();

		return Pheanstalk::create(
			$connectionOptions->getHost(),
			$connectionOptions->getPort(),
			new Timeout($connectionOptions->getTimeout())
		);
	}


}
