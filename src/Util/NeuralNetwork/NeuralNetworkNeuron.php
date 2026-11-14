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

namespace Base3\Base3Tools\Util\NeuralNetwork;

class NeuralNetworkNeuron {

	public $bias = false;
	public $inweights = array();
	public $outweights = array();
	public $sum = null;
	public $value = null;
	public $d = 0;

	public function toString() {
		$value = $this->value === null ? '-' : number_format($this->value, 2);
		if ($this->bias) {
			$str = '    Bias - value: ' . $value . "\n";
		} else {
			$sum = $this->sum === null ? '-' : number_format($this->sum, 2);
			$str = '    Neuron - sum: ' . $sum . ' - value: ' . $value . "\n";
		}
		return $str;
	}

}
