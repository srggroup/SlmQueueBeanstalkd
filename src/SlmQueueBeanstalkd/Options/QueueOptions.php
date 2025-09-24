<?php

namespace SlmQueueBeanstalkd\Options;

use Laminas\Stdlib\AbstractOptions;

class QueueOptions extends AbstractOptions {


	protected string $tube = '';


	public function getTube(): string {
		return $this->tube;
	}


	public function setTube($tube): void {
		$this->tube = $tube;
	}


}
