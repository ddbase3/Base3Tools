<?php declare(strict_types=1);

namespace Base3Tools\Scriptlock\None;

use Base3Tools\Scriptlock\Api\IScriptlock;

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
