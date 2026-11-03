<?php declare(strict_types=1);

namespace Base3Tools\Test\Scriptlock;

use Base3\Api\IClassMap;
use Base3Tools\Scriptlock\Api\IScriptlockCondition;
use Base3Tools\Scriptlock\Custom\ScriptlockCustom;
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
