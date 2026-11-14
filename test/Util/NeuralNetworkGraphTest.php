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

namespace Base3\Base3Tools\Test\Util;

use Base3\Base3Tools\Util\NeuralNetwork\NeuralNetworkLayer;
use Base3\Base3Tools\Util\NeuralNetwork\NeuralNetworkNet;
use Base3\Base3Tools\Util\NeuralNetwork\NeuralNetworkNeuron;
use Base3\Base3Tools\Util\NeuralNetwork\NeuralNetworkWeight;
use PHPUnit\Framework\TestCase;

final class NeuralNetworkGraphTest extends TestCase {

	public function testNeuronStringShowsUninitializedAndInitializedValues(): void {
		$neuron = new NeuralNetworkNeuron();
		$this->assertStringContainsString('sum: - - value: -', $neuron->toString());
		$neuron->sum = 1.234;
		$neuron->value = 0.75;
		$this->assertStringContainsString('sum: 1.23 - value: 0.75', $neuron->toString());
		$neuron->bias = true;
		$this->assertStringContainsString('Bias - value: 0.75', $neuron->toString());
	}

	public function testWeightNetAndLayerRenderTheirElements(): void {
		$weight = new NeuralNetworkWeight();
		$this->assertStringContainsString('weight: -', $weight->toString());
		$weight->weight = 0.345;
		$this->assertStringContainsString('weight: 0.35', $weight->toString());
		$net = new NeuralNetworkNet();
		$net->weights[] = $weight;
		$this->assertStringContainsString('Net - #weights: 1', $net->toString());
		$this->assertStringContainsString('Weight - weight: 0.35', $net->toString());
		$layer = new NeuralNetworkLayer();
		$layer->neurons[] = new NeuralNetworkNeuron();
		$this->assertStringContainsString('Layer - #neurons: 1', $layer->toString());
		$this->assertStringContainsString('Neuron - sum:', $layer->toString());
	}
}
