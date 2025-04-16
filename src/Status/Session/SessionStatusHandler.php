<?php declare(strict_types=1);

namespace Base3Tools\Status\Session;

use Base3\Api\ICheck;
use Base3\Session\Api\ISession;
use Base3Tools\Status\Api\IStatusHandler;

class SessionStatusHandler implements IStatusHandler, ICheck {

	private $session;

	public function __construct(ISession $session) {
		$this->session = $session;
	}

	// Implementation of IStatusHandler

	public function get($fields = null) {
		if ($fields == null) return $_SESSION["status"];
		if (!is_array($fields)) return $_SESSION["status"][$fields];
		$result = array();
		foreach ($fields as $field) $result[$field] = $_SESSION["status"][$field];
		return $result;
	}

	public function set($data) {
		foreach ($data as $key => $value) $_SESSION["status"][$key] = $value;
	}

	// Implementation of ICheck

	public function checkDependencies() {
		return array(
			"depending_services" => $this->session == null ? "Fail" : "Ok"
		);
	}

}
