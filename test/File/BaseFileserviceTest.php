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

namespace Base3\Base3Tools\Test\File;

use Base3\Base3Tools\File\Base\BaseFileservice;
use PHPUnit\Framework\TestCase;

final class BaseFileserviceTest extends TestCase {

	public function testReadsRelativeAndLeadingSlashPathsWithOptionalBase64Encoding(): void {
		if (!defined('DIR_USERFILES')) {
			define('DIR_USERFILES', sys_get_temp_dir() . '/base3tools-test-userfiles-' . getmypid() . '/');
		}
		if (!is_dir(DIR_USERFILES)) {
			mkdir(DIR_USERFILES, 0700, true);
		}
		$name = 'unit-file-' . bin2hex(random_bytes(6)) . '.txt';
		$path = DIR_USERFILES . $name;
		try {
			file_put_contents($path, "test\0data");
			$files = new BaseFileservice();
			$this->assertSame("test\0data", $files->getContents($name));
			$this->assertSame(base64_encode("test\0data"), $files->getContents('/' . $name, true));
		} finally {
			if (is_file($path)) unlink($path);
		}
	}
}
