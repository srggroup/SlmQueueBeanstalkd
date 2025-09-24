<?php

namespace SlmQueueBeanstalkd\Options;

use Laminas\Stdlib\AbstractOptions;

class ConnectionOptions extends AbstractOptions {


	protected string $host;

	protected int $port;

	protected int $timeout;


	public function setHost($host): void {
		$this->host = $host;
	}


	public function getHost(): string {
		return $this->host;
	}


	public function setPort($port): void {
		$this->port = (int) $port;
	}


	public function getPort(): int {
		return $this->port;
	}


	public function setTimeout($timeout): void {
		$this->timeout = (int) $timeout;
	}


	public function getTimeout(): int {
		return $this->timeout;
	}


}
