<?php declare(strict_types=1);

namespace Base3Tools\Scriptlock\Api;

interface IScriptlockCondition {

	// check condition, if returns true
	public function activated();

	// returns false, if no reason for a lock, true otherwise
	public function check();

}
