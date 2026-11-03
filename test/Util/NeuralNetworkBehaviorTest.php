<?php declare(strict_types=1);

namespace Base3Tools\Test\Util;

use Base3Tools\Util\NeuralNetwork\NeuralNetwork;
use Base3Tools\Util\NeuralNetwork\ReluNeuralNetworkActFunc;
use Base3Tools\Util\NeuralNetwork\SigmoidNeuralNetworkActFunc;
use PHPUnit\Framework\TestCase;

final class NeuralNetworkBehaviorTest extends TestCase {

	public function testPredictAndExportWithoutBiasUsingKnownWeights(): void {
		$network = new NeuralNetwork([
			'layers' => [2, 1], 'bias' => false,
			'weightinit' => null, 'weights' => [[[0.5, -1.0]]],
			'actfunc' => new ReluNeuralNetworkActFunc()
		]);
		$this->assertSame([1.0], $network->predict([4, 1]));
		$this->assertSame([0], $network->predict([1, 1]));
		$this->assertSame([2, 1], $network->export()['layers']);
		$this->assertFalse($network->export()['bias']);
		$this->assertSame([[[0.5, -1.0]]], $network->export()['weights']);
		$this->assertStringContainsString('NN - #layers: 2', $network->toString());
	}

	public function testBiasNeuronParticipatesInForwardPassAndExport(): void {
		$network = new NeuralNetwork([
			'layers' => [1, 1], 'bias' => true,
			'weightinit' => null, 'weights' => [[[0.3, 0.8]]],
			'actfunc' => new SigmoidNeuralNetworkActFunc()
		]);
		$this->assertEqualsWithDelta(1 / (1 + exp(-1.9)), $network->predict([2])[0], 0.0000001);
		$this->assertSame([1, 1], $network->export()['layers']);
		$this->assertTrue($network->export()['bias']);
		$this->assertSame([[[0.3, 0.8]]], $network->export()['weights']);
		$this->assertStringContainsString('Bias - value: 1.00', $network->toString());
	}

	public function testTrainingUpdatesBothOutputAndHiddenLayers(): void {
		$network = new NeuralNetwork([
			'layers' => [1, 1, 1], 'bias' => false,
			'weightinit' => null, 'weights' => [[[1.0]], [[1.0]]],
			'epsilon' => 0.1, 'actfunc' => new ReluNeuralNetworkActFunc()
		]);
		$network->train([1], [0]);
		$weights = $network->export()['weights'];
		$this->assertEqualsWithDelta(0.9, $weights[0][0][0], 0.0000001);
		$this->assertEqualsWithDelta(0.9, $weights[1][0][0], 0.0000001);
		$this->assertEqualsWithDelta(0.81, $network->predict([1])[0], 0.0000001);
	}

	public function testDefaultNetworkBuildsUsableGraphWithRandomWeights(): void {
		$network = new NeuralNetwork();
		$this->assertCount(3, $network->layers);
		$this->assertCount(2, $network->predict([0.5, 0.5]));
		$this->assertSame([2, 2, 2], $network->export()['layers']);
	}
}
