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

namespace Base3\Base3Tools\Util\NeuralNetwork\Test;

use Base3\CoreFoundation\Page\Api\IPage;
use Base3\Base3Tools\Util\NeuralNetwork\NeuralNetwork;

class NeuralNetworkTest implements IPage {

	// Implementation of IBase

	public static function getName(): string {
		return "neuralnetworktest";
	}

	// Implementation of IPage

		public function getUrl() {
				return $this->getName() . ".php";
		}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {

		$str = '';
//		$str .= '<h1>NN Test</h1>';

/*
		$nn = new \Util\NeuralNetwork\NeuralNetwork;
		$str .= '<pre>' . $nn->toString() . '</pre>';
		$nn->train(array(.7, .6), array(.9, .2));
		$str .= '<pre>' . $nn->toString() . '</pre>';
*/

		$nn = new NeuralNetwork(array(
			"layers" => array(2, 3, 5),
			"bias" => true /* ,
			"weights" => array(
				array(
					array(.3, .8, .5),
					array(-.2, -.6, .7),
					array(.5, -.2, .3)
				),
				array(
					array(.1, -.4, .9, -.2)
				)
			) */
		));
		for ($i = 0; $i < 5000; $i++) {
			$nn->train(array(0, 0), array(1, 0, 0, 0, 0));
			$nn->train(array(0, 1), array(1, 1, 0, 0, 0));
			$nn->train(array(1, 0), array(1, 1, 1, 0, 0));
			$nn->train(array(1, 1), array(1, 1, 1, 1, 0));
		}
		$str .= '<pre>' . $nn->toString() . '</pre>';

		$outdata = $nn->predict(array(0, 0));
		$outdata = array_map(function($o) { return number_format($o, 2); }, $outdata);
		$str .= '[0, 0] - [' . implode(", ", $outdata) . ']<br />';
		$outdata = $nn->predict(array(0, 1));
		$outdata = array_map(function($o) { return number_format($o, 2); }, $outdata);
		$str .= '[0, 1] - [' . implode(", ", $outdata) . ']<br />';
		$outdata = $nn->predict(array(1, 0));
		$outdata = array_map(function($o) { return number_format($o, 2); }, $outdata);
		$str .= '[1, 0] - [' . implode(", ", $outdata) . ']<br />';
		$outdata = $nn->predict(array(1, 1));
		$outdata = array_map(function($o) { return number_format($o, 2); }, $outdata);
		$str .= '[1, 1] - [' . implode(", ", $outdata) . ']<br />';

		return $str;
	}

	public function getHelp(): string {
		return 'Help of NeuralNetworkTest' . "\n";
	}
}
