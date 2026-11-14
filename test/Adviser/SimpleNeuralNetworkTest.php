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

namespace Base3\Base3Tools\Test\Adviser;

use Base3\Core\ServiceLocator;
use Base3\Base3Tools\Adviser\SimpleNeuralNetwork\SimpleNeuralNetwork;
use PHPUnit\Framework\TestCase;

final class SimpleNeuralNetworkTest extends TestCase {

	private ServiceLocator $previousLocator;

	protected function setUp(): void {
		$this->previousLocator = ServiceLocator::getInstance();
	}

	protected function tearDown(): void {
		ServiceLocator::useInstance($this->previousLocator);
	}

	public function testServiceUsesTheRegisteredDatabaseAndExposesItsName(): void {
		$database = new \stdClass();
		$container = new ServiceLocator();
		$container->set('database', $database, ServiceLocator::SHARED);
		ServiceLocator::useInstance($container);

		$adviser = new SimpleNeuralNetwork();
		$this->assertSame('simpleneuralnetwork', $adviser::getName());
		$this->assertSame($database, (new \ReflectionProperty($adviser, 'database'))->getValue($adviser));
	}
}
