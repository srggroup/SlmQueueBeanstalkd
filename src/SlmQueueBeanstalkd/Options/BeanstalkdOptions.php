<?php

namespace SlmQueueBeanstalkd\Options;

use Laminas\Stdlib\AbstractOptions;

class BeanstalkdOptions extends AbstractOptions {


	protected ConnectionOptions $connection;


	public function setConnection(array $options): void {
		$this->connection = new ConnectionOptions($options);
	}


	public function getConnection(): ConnectionOptions {
		return $this->connection;
	}


}
