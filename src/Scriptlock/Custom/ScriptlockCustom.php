<?php declare(strict_types=1);

namespace Base3Tools\Scriptlock\Custom;

use Base3\Api\ICheck;
use Base3\Api\IClassMap;
use Base3Tools\Scriptlock\Api\IScriptlock;
use Base3Tools\Scriptlock\Api\IScriptlockCondition;

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
