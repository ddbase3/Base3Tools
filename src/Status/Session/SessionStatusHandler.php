<?php declare(strict_types=1);

/***********************************************************************
 * This file is part of Base3Tools for BASE3 Framework.
 *
 * Base3Tools collects reusable services and utilities for BASE3 projects.
 * It includes file helpers, desktop abstractions and messaging utilities.
 * Search, observation and miscellaneous tools are available as modules.
 *
 * Developed by Daniel Dahme
 * Licensed under GPL-3.0
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * https://base3.de
 * https://github.com/ddbase3/Base3Tools
 **********************************************************************/

namespace Base3\Base3Tools\Status\Session;

use Base3\CoreFoundation\Api\ICheck;
use Base3\CoreFoundation\Session\Api\ISession;
use Base3\Base3Tools\Status\Api\IStatusHandler;

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
