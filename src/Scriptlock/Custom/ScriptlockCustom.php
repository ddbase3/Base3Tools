<?php declare(strict_types=1);

namespace Base3Tools\Scriptlock\Custom;

use Base3\Core\ServiceLocator;
use Base3Tools\Scriptlock\Api\IScriptlock;
use Base3\Api\ICheck;

class ScriptlockCustom implements IScriptlock, ICheck {

	private $servicelocator;
	private $classmap;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
		$this->classmap = $this->servicelocator->get('classmap');
		if ($this->check()) $this->lock();
	}

	// Implementation of IScriptlock

	public function check() {
		$conditions = $this->classmap->getInstancesByInterface(\Base3Tools\Scriptlock\Api\IScriptlockCondition::class);
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
			"depending_services" => $this->servicelocator->get('classmap') == null ? "Fail" : "Ok"
		);
	}

}
