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

namespace Base3\Base3Tools\Test\Scriptlock;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\Base3Tools\Scriptlock\Api\IScriptlockCondition;
use Base3\Base3Tools\Scriptlock\Custom\ScriptlockCustom;
use PHPUnit\Framework\TestCase;

final class ScriptlockCustomTest extends TestCase {

	public function testNoConditionsLeavesScriptUnlocked(): void {
		$map = $this->createMock(IClassMap::class);
		$map->expects($this->exactly(2))->method('getInstancesByInterface')->with(IScriptlockCondition::class)->willReturn([]);
		$lock = new ScriptlockCustom($map);
		$this->assertFalse($lock->check());
		$this->assertSame(['check' => 'Ok'], $lock->checkDependencies());
	}

	public function testOnlyActivatedAndMatchingConditionsLock(): void {
		$inactive = $this->createMock(IScriptlockCondition::class);
		$inactive->method('activated')->willReturn(false);
		$inactive->expects($this->never())->method('check');
		$active = $this->createStub(IScriptlockCondition::class);
		$active->method('activated')->willReturn(true);
		$active->method('check')->willReturn(true);
		$map = $this->createStub(IClassMap::class);
		$calls = 0;
		$map->method('getInstancesByInterface')->willReturnCallback(static function() use (&$calls, $inactive, $active): array {
			return $calls++ === 0 ? [] : [$inactive, $active];
		});
		$lock = new ScriptlockCustom($map);

		$this->assertTrue($lock->check());
	}

	public function testActivatedConditionWithoutMatchDoesNotLock(): void {
		$condition = $this->createStub(IScriptlockCondition::class);
		$condition->method('activated')->willReturn(true);
		$condition->method('check')->willReturn(false);
		$map = $this->createStub(IClassMap::class);
		$map->method('getInstancesByInterface')->willReturn([$condition]);
		$this->assertFalse((new ScriptlockCustom($map))->check());
	}
}
