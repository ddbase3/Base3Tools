<?php declare(strict_types=1);

namespace Base3Tools\Test\Adviser;

use Base3\Core\ServiceLocator;
use Base3Tools\Adviser\SimpleNeuralNetwork\SimpleNeuralNetwork;
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
