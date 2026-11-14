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

namespace Base3\Base3Tools\Scriptlock\None;

use Base3\Base3Tools\Scriptlock\Api\IScriptlock;

class ScriptlockNone implements IScriptlock {

	public function __construct() {
	}

	// Implementation of IScriptlock

	public function check() {
		return false;
	}

	public function lock() {
	}

}
