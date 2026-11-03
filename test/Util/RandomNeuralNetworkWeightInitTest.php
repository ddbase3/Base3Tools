<?php declare(strict_types=1);

namespace Base3Tools\Test\Util;

use Base3Tools\Util\NeuralNetwork\NeuralNetwork;
use Base3Tools\Util\NeuralNetwork\RandomNeuralNetworkWeightInit;
use PHPUnit\Framework\TestCase;

final class RandomNeuralNetworkWeightInitTest extends TestCase {

	public function testGeneratedWeightsRespectTopologyAndConfiguredBounds(): void {
		$init = new RandomNeuralNetworkWeightInit(-1, 1, 10);
		$network = new NeuralNetwork(['layers' => [2, 3, 1], 'bias' => true, 'weightinit' => $init]);
		$weights = $network->export()['weights'];
		$this->assertCount(2, $weights);
		$this->assertCount(3, $weights[0]);
		$this->assertCount(3, $weights[0][0]);
		$this->assertCount(1, $weights[1]);
		$this->assertCount(4, $weights[1][0]);
		foreach ($weights as $layer) {
			foreach ($layer as $neuron) {
				foreach ($neuron as $value) {
					$this->assertGreaterThanOrEqual(-1.0, $value);
					$this->assertLessThanOrEqual(1.0, $value);
				}
			}
		}
	}

	public function testZeroBoundInitializerCreatesDeterministicZeroWeights(): void {
		$network = new NeuralNetwork(['layers' => [1, 1], 'bias' => false, 'weightinit' => new RandomNeuralNetworkWeightInit(0, 0)]);
		$this->assertSame([[[0]]], $network->export()['weights']);
	}
}
