<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use SlmQueue\Queue\QueuePluginManager;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use SlmQueueBeanstalkd\Worker\BeanstalkdWorker;

/**
 * BeanstalkdWorkerControllerFactory
 */
class BeanstalkdWorkerControllerFactory implements FactoryInterface {


	public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null) {
		$worker = $container->get(BeanstalkdWorker::class);
		$manager = $container->get(QueuePluginManager::class);

		return new BeanstalkdWorkerController($worker, $manager);
	}


}
