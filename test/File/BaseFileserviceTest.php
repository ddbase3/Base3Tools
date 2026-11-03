<?php declare(strict_types=1);

namespace Base3Tools\Test\File;

use Base3Tools\File\Base\BaseFileservice;
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
