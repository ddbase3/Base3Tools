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

namespace Base3\Base3Tools\Test\Status;

use Base3\CoreFoundation\Accesscontrol\Api\IAccesscontrol;
use Base3\Base3Tools\Status\File\FileStatusHandler;
use PHPUnit\Framework\TestCase;

final class FileStatusHandlerTest extends TestCase {

	private string $userId;

	protected function setUp(): void {
		if (!defined('DIR_LOCAL')) {
			define('DIR_LOCAL', sys_get_temp_dir() . '/base3tools-test-local-' . getmypid() . '/');
		}
		if (!is_dir(DIR_LOCAL . 'FileStatus')) {
			mkdir(DIR_LOCAL . 'FileStatus', 0700, true);
		}
		$this->userId = 'unit-' . bin2hex(random_bytes(8));
	}

	protected function tearDown(): void {
		$path = DIR_LOCAL . 'FileStatus/' . $this->userId . '.json';
		if (is_file($path)) unlink($path);
	}

	public function testMissingFileReadsEmptyAndWritesMergeExistingStatus(): void {
		$handler = $this->handler($this->userId);
		$this->assertSame([], $handler->get());
		$this->assertNull($handler->get('missing'));
		$this->assertSame([], $handler->get(['missing']));
		$handler->set(['a' => 1, 'b' => null, 'c' => true]);
		$handler->set(['a' => 2, 'd' => ['nested' => 'yes']]);
		$this->assertSame(['a' => 2, 'b' => null, 'c' => true, 'd' => ['nested' => 'yes']], $handler->get());
		$this->assertSame(2, $handler->get('a'));
		$this->assertSame(['a' => 2, 'd' => ['nested' => 'yes']], $handler->get(['a', 'd', 'missing']));
		$this->assertSame(['depending_services' => 'Ok'], $handler->checkDependencies());
	}

	public function testMissingUserIdHasNoStatusFile(): void {
		$handler = $this->handler(null);
		$this->assertNull($handler->get());
		$this->assertNull($handler->get('anything'));
	}

	private function handler(?string $userId): FileStatusHandler {
		$accesscontrol = $this->createStub(IAccesscontrol::class);
		$accesscontrol->method('getUserId')->willReturn($userId);
		return new FileStatusHandler($accesscontrol);
	}
}
