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

namespace Base3\Base3Tools\File\Base;

use Base3\Base3Tools\File\Api\IFileservice;

class BaseFileservice implements IFileservice {

	private function getFullPath($filename) {
		return DIR_USERFILES . ltrim($filename, DIRECTORY_SEPARATOR);
	}

	// Implementation of IFileservice

	public function getContents($filename, $base64 = false) {
		$content = file_get_contents($this->getFullPath($filename));
		return $base64 ? base64_encode($content) : $content;
	}

}
