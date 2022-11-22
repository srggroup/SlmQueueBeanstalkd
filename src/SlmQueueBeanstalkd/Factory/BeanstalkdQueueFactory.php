<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueueBeanstalkd\Options\QueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;

/**
 * BeanstalkdQueueFactory
 */
class BeanstalkdQueueFactory implements FactoryInterface
{

	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$pheanstalk       = $container->get('SlmQueueBeanstalkd\Service\PheanstalkService');
		$jobPluginManager = $container->get('SlmQueue\Job\JobPluginManager');

		$queueOptions = $this->getQueueOptions($parentLocator, $requestedName);

		return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
	}


	/**
	 * Returns custom beanstalkd options for specified queue
	 *
	 * @param ContainerInterface $container
	 * @param string             $queueName
	 *
	 * @return QueueOptions
	 */
    protected function getQueueOptions(ContainerInterface $container, $queueName)
    {
        $config = $container->get('config');
        $queuesOptions = $config['slm_queue']['queues'] ?? [];
        $queueOptions = $queuesOptions[$queueName] ?? [];

        return new QueueOptions($queueOptions);
    }
}
