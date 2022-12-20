<?php

namespace SlmQueueBeanstalkd\Queue;

use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Job as PheanstalkJob;
use Pheanstalk\Pheanstalk;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;
use SlmQueueBeanstalkd\Options\QueueOptions;

/**
 * BeanstalkdQueue
 */
class BeanstalkdQueue extends AbstractQueue implements BeanstalkdQueueInterface
{
    /**
     * @var Pheanstalk
     */
    protected $pheanstalk;

    /**
     * @var string
     */
    protected $tubeName;

    /**
     * Constructor
     *
     * @param Pheanstalk       $pheanstalk
     * @param string           $name
     * @param JobPluginManager $jobPluginManager
     */
    public function __construct(
        Pheanstalk $pheanstalk,
        $name,
        JobPluginManager $jobPluginManager,
        QueueOptions $options = null
    ) {
        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $name;
        if (($options !== null) && $options->getTube()) {
            $this->tubeName = $options->getTube();
        }
        parent::__construct($name, $jobPluginManager);
    }

    /**
     * Valid options are:
     *      - priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
     *      - delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
     *      - ttr: in seconds, how much time a job can be reserved for (default to 60)
     *
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = []): void
    {
        $pheanstalkJob = $this->pheanstalk->put(
            $this->serializeJob($job),
	        $options['priority'] ?? PheanstalkInterface::DEFAULT_PRIORITY,
	        $options['delay'] ?? PheanstalkInterface::DEFAULT_DELAY,
	        $options['ttr'] ?? PheanstalkInterface::DEFAULT_TTR
        );

        $job->setId($pheanstalkJob->getId());
    }

    /**
     * Valid option is:
     *      - timeout: by default, when we ask for a job, it will block until a job is found (possibly forever if
     *                 new jobs never come). If you set a timeout (in seconds), it will return after the timeout is
     *                 expired, even if no jobs were found
     *
     * {@inheritDoc}
     */
    public function pop(array $options = []): ?JobInterface
    {
        $job = $this->pheanstalk->reserveWithTimeout($options['timeout'] ?? null);

        if (!$job instanceof PheanstalkJob) {
            return null;
        }

        return $this->unserializeJob($job->getData(), ['__id__' => $job->getId()]);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(JobInterface $job): void
    {
        $this->pheanstalk->delete($job);
    }

    /**
     * Valid options are:
     *      - priority: the lower the priority is, the sooner the job get popped from the queue (default to 1024)
     *      - delay: the delay in seconds before a job become available to be popped (default to 0 - no delay -)
     *
     * {@inheritDoc}
     */
    public function release(JobInterface $job, array $options = [])
    {
        $this->pheanstalk->release(
            $job,
	        $options['priority'] ?? PheanstalkInterface::DEFAULT_PRIORITY,
	        $options['delay'] ?? PheanstalkInterface::DEFAULT_DELAY
        );
    }

    /**
     * Valid option is:
     *      - priority: the lower the priority is, the sooner the job get kicked
     *
     * {@inheritDoc}
     */
    public function bury(JobInterface $job, array $options = [])
    {
        $this->pheanstalk->bury(
            $job,
	        $options['priority'] ?? PheanstalkInterface::DEFAULT_PRIORITY
        );
    }

    /**
     * {@inheritDoc}
     */
    public function kick($max)
    {
        $this->pheanstalk->useTube($this->getTubeName());
        return $this->pheanstalk->kick($max);
    }

    /**
     * Get the name of the beanstalkd tube that is used for storing queue
     * @return string
     */
    public function getTubeName()
    {
        return $this->tubeName;
    }
}
