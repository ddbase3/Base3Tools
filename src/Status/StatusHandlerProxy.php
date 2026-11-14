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

namespace Base3\Base3Tools\Status;

use Base3\CoreFoundation\Api\ICheck;
use Base3\Base3Tools\Status\Api\IStatusHandler;

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
