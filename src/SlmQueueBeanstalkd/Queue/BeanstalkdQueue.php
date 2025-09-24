<?php

namespace SlmQueueBeanstalkd\Queue;

use Pheanstalk\Contract\PheanstalkPublisherInterface;
use Pheanstalk\Pheanstalk;
use Pheanstalk\Values\JobId;
use Pheanstalk\Values\TubeName;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;
use SlmQueueBeanstalkd\Options\QueueOptions;

class BeanstalkdQueue extends AbstractQueue implements BeanstalkdQueueInterface {


	protected string $tubeName;


	public function __construct(
		protected Pheanstalk $pheanstalk,
		$name,
		JobPluginManager $jobPluginManager,
		?QueueOptions $options = null
	) {
		$this->tubeName = $name;
		if (($options !== null) && $options->getTube()) {
			$this->tubeName = $options->getTube();
		}
		parent::__construct($name, $jobPluginManager);
	}


	public function push(JobInterface $job, array $options = []): void {
		$pheanstalkJob = $this->pheanstalk->put(
			$this->serializeJob($job),
			$options['priority'] ?? PheanstalkPublisherInterface::DEFAULT_PRIORITY,
			$options['delay'] ?? PheanstalkPublisherInterface::DEFAULT_DELAY,
			$options['ttr'] ?? PheanstalkPublisherInterface::DEFAULT_TTR
		);

		$job->setId($pheanstalkJob->getId());
	}


	/**
	 * Valid option is:
	 *      - timeout: by default, when we ask for a job, it will block until a job is found (possibly forever if
	 *                 new jobs never come). If you set a timeout (in seconds), it will return after the timeout is
	 *                 expired, even if no jobs were found
	 */
	public function pop(array $options = []): ?JobInterface {
		$job = $this->pheanstalk->reserveWithTimeout($options['timeout'] ?? null);

		if ($job === null) {
			return null;
		}

		return $this->unserializeJob($job->getData(), ['__id__' => $job->getId()]);
	}


	public function delete(JobInterface $job): void {
		$this->pheanstalk->delete(new JobId($job->getId()));
	}


	/**
	 * Valid options are:
	 *      - priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
	 *      - delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
	 *
	 * {@inheritDoc}
	 */
	public function release(JobInterface $job, array $options = []): void {
		$this->pheanstalk->release(
			new JobId($job->getId()),
			$options['priority'] ?? PheanstalkPublisherInterface::DEFAULT_PRIORITY,
			$options['delay'] ?? PheanstalkPublisherInterface::DEFAULT_DELAY
		);
	}


	/**
	 * Valid option is:
	 *      - priority: the lower the priority is, the sooner the job get kicked
	 *
	 * {@inheritDoc}
	 */
	public function bury(JobInterface $job, array $options = []): void {
		$this->pheanstalk->bury(
			new JobId($job->getId()),
			$options['priority'] ?? PheanstalkPublisherInterface::DEFAULT_PRIORITY
		);
	}


	/**
	 * {@inheritDoc}
	 */
	public function kick(int $max): int {
		$this->pheanstalk->useTube(new TubeName($this->getTubeName()));

		return $this->pheanstalk->kick($max);
	}


	/**
	 * Get the name of the beanstalkd tube that is used for storing queue
	 */
	public function getTubeName(): string {
		return $this->tubeName;
	}


}
