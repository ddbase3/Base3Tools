<?php declare(strict_types=1);

namespace Base3Tools\Test\Downloader;

use Base3Tools\Downloader\Base\BaseDownloader;
use PHPUnit\Framework\TestCase;

final class BaseDownloaderTest extends TestCase {

	public function testLocalFileDownloadSupportsRawAndBase64Content(): void {
		$path = tempnam(sys_get_temp_dir(), 'b3t-download-');
		$this->assertNotFalse($path);
		try {
			file_put_contents($path, "raw\0payload\n");
			$downloader = new BaseDownloader();
			$this->assertSame("raw\0payload\n", $downloader->download($path));
			$this->assertSame(base64_encode("raw\0payload\n"), $downloader->download($path, true));
		} finally {
			unlink($path);
		}
	}
}
