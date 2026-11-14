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

namespace Base3\Base3Tools\Util\MobileDetect\Test;

use Base3\CoreFoundation\Page\Api\IPage;
use Base3\Base3Tools\Util\MobileDetect\MobileDetect;

class MobileDetectTest implements IPage {

	// Implementation of IBase

	public static function getName(): string {
		return "mobiledetecttest";
	}

	// Implementation of IPage

		public function getUrl() {
				return $this->getName() . ".php";
		}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {
		$str = '<h1>MobileDetectTest</h1>';

		$md = new MobileDetect;
		$str .= '<p>mobile? - ' . ( $md->isMobile() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>tablet? - ' . ( $md->isTablet() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>phone? - ' . ( $md->isMobile() && !$md->isTablet() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>ios? - ' . ( $md->isiOS() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>android? - ' . ( $md->isAndroidOS() ? 'yes' : 'no' ) . ' - ' . $md->version('Android') . '</p>';
		$str .= '<p>chrome? - ' . ( $md->is('Chrome') ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>samsung? - ' . ( $md->isSamsung() ? 'yes' : 'no' ) . '</p>';
		$str .= '';  // version(Windows NT)

		return $str;
	}

	public function getHelp(): string {
		return 'Help of MobileDetectTest' . "\n";
	}
}
