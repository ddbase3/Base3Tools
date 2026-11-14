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

use Base3\Base3Tools\Util\NeuralNetwork\ReluNeuralNetworkActFunc;
use Base3\Base3Tools\Util\NeuralNetwork\SigmoidNeuralNetworkActFunc;
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
