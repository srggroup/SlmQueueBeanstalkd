<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Pheanstalk\Pheanstalk;
use SlmQueue\Job\JobPluginManager;
use SlmQueueBeanstalkd\Options\QueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;

class BeanstalkdQueueFactory implements FactoryInterface {


	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): BeanstalkdQueue {
		/** @var Pheanstalk $pheanstalk */
		$pheanstalk = $container->get('SlmQueueBeanstalkd\Service\PheanstalkService');
		$jobPluginManager = $container->get(JobPluginManager::class);

		$queueOptions = $this->getQueueOptions($container, $requestedName);

		return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
	}


	/**
	 * Returns custom beanstalkd options for specified queue
	 *
	 * @param string $queueName
	 */
	protected function getQueueOptions(ContainerInterface $container, $queueName): QueueOptions {
		$config = $container->get('config');
		$queuesOptions = $config['slm_queue']['queues'] ?? [];
		$queueOptions = $queuesOptions[$queueName] ?? [];

		return new QueueOptions($queueOptions);
	}


}
