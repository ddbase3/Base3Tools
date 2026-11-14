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

namespace Base3\Base3Tools\Test\Observation;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\Base3Tools\Observation\Api\IObserver;
use Base3\Base3Tools\Observation\Base\Observation;
use PHPUnit\Framework\TestCase;

final class ObservationTest extends TestCase {

	private string $name;

	protected function setUp(): void {
		if (!defined('DIR_LOCAL')) {
			define('DIR_LOCAL', sys_get_temp_dir() . '/base3tools-test-local-' . getmypid() . '/');
		}
		if (!is_dir(DIR_LOCAL . 'Observation')) {
			mkdir(DIR_LOCAL . 'Observation', 0700, true);
		}
		$this->name = 'unit-' . bin2hex(random_bytes(8));
	}

	protected function tearDown(): void {
		$path = $this->path();
		if (is_file($path)) unlink($path);
	}

	public function testDuplicateRegistrationsArePersistedOnlyOnceAndDispatchedOnce(): void {
		$spy = new ObserverSpy();
		$map = $this->createMock(IClassMap::class);
		$map->expects($this->once())->method('getInstanceByInterfaceName')
			->with(IObserver::class, ObserverSpy::getName())->willReturn($spy);
		$service = new Observation($map);
		$service->addObserver($this->name, $spy);
		$service->addObserver($this->name, $spy);

		$this->assertSame([ObserverSpy::getName()], json_decode((string)file_get_contents($this->path()), true));
		$payload = (object)['id' => 17];
		$service->notifyObservers($this->name, 'updated', $payload);
		$this->assertSame([['updated', $payload]], $spy->notifications);
	}

	public function testRemoveLastObserverDeletesExistingFile(): void {
		$spy = new ObserverSpy();
		$map = $this->createStub(IClassMap::class);
		$service = new Observation($map);
		$service->addObserver($this->name, $spy);
		$this->assertFileExists($this->path());
		$service->removeObserver($this->name, $spy);
		$this->assertFileDoesNotExist($this->path());
	}

	public function testEmptyListAndUnresolvableObserversAreIgnored(): void {
		$map = $this->createMock(IClassMap::class);
		$map->expects($this->once())->method('getInstanceByInterfaceName')->with(IObserver::class, 'unknown')->willReturn(null);
		$service = new Observation($map);
		$service->notifyObservers($this->name);
		file_put_contents($this->path(), '["unknown"]');
		$service->notifyObservers($this->name, 'created');
		$this->assertFileExists($this->path());
	}

	private function path(): string {
		return DIR_LOCAL . 'Observation/' . $this->name . '.json';
	}
}
