<?php

namespace SlmQueueBeanstalkd\Worker\Exception;

use InvalidArgumentException;
use SlmQueueBeanstalkd\Exception\ExceptionInterface;

class InvalidQueueException extends InvalidArgumentException implements ExceptionInterface {


}
