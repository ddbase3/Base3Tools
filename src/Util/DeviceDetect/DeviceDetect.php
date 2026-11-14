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

namespace Base3\Base3Tools\Util\DeviceDetect;

class DeviceDetect {

	// https://en.wikipedia.org/wiki/Samsung_Galaxy
	// Treffer von oben nach unten
	private $models = array(
		"Windows NT 5.1" => "Windows XP PC",
		"Windows NT 6.0" => "Windows Vista PC",
		"Windows NT 6.1" => "Windows 7 PC",
		"Windows NT 6.2" => "Windows 8 PC",
		"Windows NT 10.0" => "Windows 10 PC",

		"GT-I930" => "Samsung Galaxy S3",
		"GT-I950" => "Samsung Galaxy S4",
		"SM-G920" => "Samsung Galaxy S6",
		"SM-G925" => "Samsung Galaxy S6 Edge",
		"SM-G930" => "Samsung Galaxy S7",
		"SM-G935" => "Samsung Galaxy S7 Edge",
		"SM-G950" => "Samsung Galaxy S8",
		"SM-G955" => "Samsung Galaxy S8+",
		"SM-G960" => "Samsung Galaxy S9",
		"SM-G965" => "Samsung Galaxy S9+",
		"SM-G970" => "Samsung Galaxy S10",
		"SM-G975" => "Samsung Galaxy S10+",
		"SM-G977" => "Samsung Galaxy S10 5G",

		"Windows Phone" => "Windows Phone",
		"Windows" => "Windows PC",
		"Linux" => "Linux PC",
		"Android" => "Android Device",
		"Macintosh" => "Mac",
		"iPhone" => "iPhone",
		"iPad" => "iPad"
	);

	public function getDevice() {
		$ua = $_SERVER['HTTP_USER_AGENT'];
		foreach ($this->models as $model => $name)
			if (strpos($ua, $model) !== false)
				return $name;
		return "Unknown device (" . $ua . ")";
	}

}
