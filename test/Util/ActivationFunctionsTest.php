<?php declare(strict_types=1);

namespace Base3Tools\Test\Util;

use Base3Tools\Util\NeuralNetwork\ReluNeuralNetworkActFunc;
use Base3Tools\Util\NeuralNetwork\SigmoidNeuralNetworkActFunc;
use PHPUnit\Framework\TestCase;

final class ActivationFunctionsTest extends TestCase {

	public function testReluHasCorrectValuesAndDerivativeAtZero(): void {
		$relu = new ReluNeuralNetworkActFunc();
		$this->assertSame(0, $relu->actfunc(-3));
		$this->assertSame(0, $relu->actfunc(0));
		$this->assertSame(3, $relu->actfunc(3));
		$this->assertSame(0, $relu->difffunc(-3));
		$this->assertSame(0, $relu->difffunc(0));
		$this->assertSame(1, $relu->difffunc(3));
	}

	public function testSigmoidHasExpectedSymmetryAndGradient(): void {
		$sigmoid = new SigmoidNeuralNetworkActFunc();
		$this->assertEqualsWithDelta(0.5, $sigmoid->actfunc(0), 0.000001);
		$this->assertEqualsWithDelta(1.0, $sigmoid->actfunc(-2) + $sigmoid->actfunc(2), 0.000001);
		$this->assertEqualsWithDelta(0.25, $sigmoid->difffunc(0), 0.000001);
	}
}
