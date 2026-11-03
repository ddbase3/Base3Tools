<?php declare(strict_types=1);

namespace Base3Tools\Test\Scriptlock;

use Base3Tools\Scriptlock\None\ScriptlockNone;
use PHPUnit\Framework\TestCase;

final class ScriptlockNoneTest extends TestCase {

	public function testNoneScriptlockNeverLocks(): void {
		$lock = new ScriptlockNone();
		$this->assertFalse($lock->check());
		$this->assertNull($lock->lock());
	}
}
