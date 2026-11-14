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

namespace Base3\Base3Tools\Util\DeviceDetect\Test;

use Base3\CoreFoundation\Page\Api\IPage;
use Base3\Base3Tools\Util\DeviceDetect\DeviceDetect;

class DeviceDetectTest implements IPage {

	// Implementation of IBase

	public static function getName(): string {
		return "devicedetecttest";
	}

	// Implementation of IPage

		public function getUrl() {
				return $this->getName() . ".php";
		}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {
		$str = '<h1>DeviceDetectTest</h1>';
		$dd = new DeviceDetect;
		$str .= $dd->getDevice();
		return $str;
	}

	public function getHelp(): string {
		return 'Help of DeviceDetectTest' . "\n";
	}
}
