<?php declare(strict_types=1);

namespace Base3Tools\Downloader\Api;

interface IDownloader {

	public function download($url, $base64 = false);

}
