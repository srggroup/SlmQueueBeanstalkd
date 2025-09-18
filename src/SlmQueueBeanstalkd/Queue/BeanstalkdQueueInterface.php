<?php

namespace SlmQueueBeanstalkd\Queue;

use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;

/**
 * Contract for a Beanstalkd queue
 */
interface BeanstalkdQueueInterface extends QueueInterface {


	/**
	 * Put a job that was popped back to the queue
	 *
	 * @return mixed
	 */
	public function release(JobInterface $job, array $options = []);


	/**
	 * Bury a job. When a job is buried, it won't be retrieved from the queue, unless the job is kicked
	 *
	 * @return void
	 */
	public function bury(JobInterface $job, array $options = []);


	/**
	 * Kick a specified number of buried jobs, hence making them "ready" again
	 *
	 * @param  int $max The maximum jobs to kick
	 * @return int Number of jobs kicked
	 */
	public function kick($max);


}
