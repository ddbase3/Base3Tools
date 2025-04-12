<?php declare(strict_types=1);

namespace Base3Tools\Status;

use Base3Tools\Status\Api\IStatusHandler;

class StatusHandlerProxy implements IStatusHandler {

	private $connector;

	public function __construct($connector) {
		$this->connector = $connector;
	}

	public function get($fields = null) {
		return $this->connector->get($fields);
	}

	public function set($data) {
		$this->connector->set($data);
	}

}
