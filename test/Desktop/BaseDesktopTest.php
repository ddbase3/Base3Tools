<?php declare(strict_types=1);

namespace Base3Tools\Test\Desktop;

use Base3\Core\ServiceLocator;
use Base3\Database\Api\IDatabase;
use Base3Tools\Desktop\Base\BaseDesktop;
use PHPUnit\Framework\TestCase;

final class BaseDesktopTest extends TestCase {

	private ServiceLocator $previousLocator;

	protected function setUp(): void {
		$this->previousLocator = ServiceLocator::getInstance();
	}

	protected function tearDown(): void {
		ServiceLocator::useInstance($this->previousLocator);
	}

	public function testAddTaskWritesTaskAndUserAssociations(): void {
		$statements = [];
		$db = $this->createMock(IDatabase::class);
		$db->expects($this->once())->method('connect');
		$db->method('escape')->willReturnCallback(static fn(string $value): string => addslashes($value));
		$db->expects($this->exactly(2))->method('nonQuery')->willReturnCallback(static function(string $sql) use (&$statements): void {
			$statements[] = $sql;
		});
		$desktop = $this->desktop($db);

		$id = $desktop->addTask("news'letter", ['id' => 7], ['alice', "o'brian"]);

		$this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $id);
		$this->assertStringContainsString('INSERT INTO `task`', $statements[0]);
		$this->assertStringContainsString("news\\'letter", $statements[0]);
		$this->assertStringContainsString('{\\"id\\":7}', $statements[0]);
		$this->assertStringContainsString('0x' . $id, $statements[0]);
		$this->assertStringContainsString('INSERT INTO `taskuser`', $statements[1]);
		$this->assertStringContainsString("o\\'brian", $statements[1]);
		$this->assertSame(2, substr_count($statements[1], '0x' . $id));
	}

	public function testAddingWithoutUsersOnlyInsertsTask(): void {
		$db = $this->createMock(IDatabase::class);
		$db->expects($this->once())->method('connect');
		$db->expects($this->once())->method('nonQuery');
		$db->method('escape')->willReturnArgument(0);

		$this->desktop($db)->addTask('plain', [], []);
	}

	public function testTaskCountsAndWaitingCountsUseDatabaseResults(): void {
		$db = $this->createMock(IDatabase::class);
		$db->expects($this->exactly(2))->method('connect');
		$db->method('escape')->willReturnArgument(0);
		$db->expects($this->exactly(2))->method('scalarQuery')
			->willReturnCallback(static function(string $sql): string {
				return str_contains($sql, '`finished`') ? '3' : '8';
			});
		$desktop = $this->desktop($db);

		$this->assertSame(8, $desktop->numTasks('review'));
		$this->assertSame(3, $desktop->waitingTasks('review'));
	}

	public function testDeleteByIdAndByTypeUseTheirRespectiveQueries(): void {
		$statements = [];
		$db = $this->createMock(IDatabase::class);
		$db->expects($this->exactly(2))->method('connect');
		$db->method('escape')->willReturnArgument(0);
		$db->method('nonQuery')->willReturnCallback(static function(string $sql) use (&$statements): void {
			$statements[] = $sql;
		});
		$desktop = $this->desktop($db);

		$desktop->removeTask('abcdef');
		$desktop->removeTasks('review');

		$this->assertStringContainsString('`id` = 0xabcdef', $statements[0]);
		$this->assertStringContainsString("`type` = 'review'", $statements[1]);
	}

	public function testResultsAreDecodedAndIndexedById(): void {
		$db = $this->createMock(IDatabase::class);
		$db->expects($this->once())->method('connect');
		$db->method('escape')->willReturnArgument(0);
		$db->method('multiQuery')->willReturn([
			['id' => 'a1', 'result' => '{"approved":true}'],
			['id' => 'b2', 'result' => '[1,2]']
		]);

		$results = $this->desktop($db)->getResults('review');
		$this->assertSame(['a1', 'b2'], array_keys($results));
		$this->assertTrue($results['a1']->approved);
		$this->assertSame([1, 2], $results['b2']);
	}

	public function testDependenciesReflectPresenceOfRegisteredDatabase(): void {
		$db = $this->createStub(IDatabase::class);
		$this->assertSame(['depending_services' => 'Ok'], $this->desktop($db)->checkDependencies());

		ServiceLocator::useInstance(new ServiceLocator());
		$this->assertSame(['depending_services' => 'Fail'], (new BaseDesktop())->checkDependencies());
	}

	private function desktop(IDatabase $database): BaseDesktop {
		$locator = new ServiceLocator();
		$locator->set('database', $database, ServiceLocator::SHARED);
		ServiceLocator::useInstance($locator);
		return new BaseDesktop();
	}
}
