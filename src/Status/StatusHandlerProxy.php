<?php declare(strict_types=1);

namespace Base3Tools\Status;

use Base3\Api\ICheck;
use Base3Tools\Status\Api\IStatusHandler;

class StatusHandlerProxy implements IStatusHandler, ICheck {

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

	// Implementation of ICheck

	public function checkDependencies() {
		return $this->connector instanceof ICheck ? $this->connector->checkDependencies() : [];
	}
}
