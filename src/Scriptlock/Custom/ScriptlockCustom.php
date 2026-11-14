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

namespace Base3\Base3Tools\Scriptlock\Custom;

use Base3\CoreFoundation\Api\ICheck;
use Base3\CoreFoundation\Api\IClassMap;
use Base3\Base3Tools\Scriptlock\Api\IScriptlock;
use Base3\Base3Tools\Scriptlock\Api\IScriptlockCondition;

class ScriptlockCustom implements IScriptlock, ICheck {

	private $classmap;

	public function __construct(IClassMap $classmap) {
		$this->classmap = $classmap;
		if ($this->check()) $this->lock();
	}

	// Implementation of IScriptlock

	public function check() {
		$conditions = $this->classmap->getInstancesByInterface(IScriptlockCondition::class);
		foreach ($conditions as $condition)
			if ($condition->activated() && $condition->check()) return true;
		return false;
	}

	public function lock() {
		// TODO execute list of IScriptlockExecution (ordered)
		exit;
	}

	// Implementation of ICheck

	public function checkDependencies() {
		return array(
			"check" => "Ok"
		);
	}

}
