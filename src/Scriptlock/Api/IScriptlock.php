<?php declare(strict_types=1);

namespace Base3Tools\Scriptlock\Api;

interface IScriptlock {

	// returns false, if no reason for a lock, true otherwise
	public function check();

	// what to do on lock
	public function lock();

}
