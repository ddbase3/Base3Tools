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

class NeuralNetworkWeight {

	public $weight = null;
	public $delta = null;
	public $prevneuron = null;
	public $nextneuron = null;

	public function toString() {
		$weight = $this->weight == null ? '-' : number_format($this->weight, 2);
		$delta = $this->delta == null ? '-' : number_format($this->delta, 2);
		// $str = '    Weight - weight: ' . $weight . ' - delta: ' . $delta . "\n";
		$str = '    Weight - weight: ' . $weight . "\n";
		return $str;
	}

}
