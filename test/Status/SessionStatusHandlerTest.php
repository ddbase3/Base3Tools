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

use Base3\CoreFoundation\Session\Api\ISession;
use Base3\Base3Tools\Status\Session\SessionStatusHandler;
use PHPUnit\Framework\TestCase;

final class SessionStatusHandlerTest extends TestCase {

	private mixed $previousSession;

	protected function setUp(): void {
		$this->previousSession = $_SESSION ?? null;
		$_SESSION = ['status' => ['old' => 'value', 'count' => 1]];
	}

	protected function tearDown(): void {
		if ($this->previousSession === null) {
			unset($_SESSION);
		} else {
			$_SESSION = $this->previousSession;
		}
	}

	public function testGetAllSelectedAndOneFieldAndMerge(): void {
		$session = $this->createStub(ISession::class);
		$handler = new SessionStatusHandler($session);
		$this->assertSame(['old' => 'value', 'count' => 1], $handler->get());
		$this->assertSame('value', $handler->get('old'));
		$this->assertSame(['count' => 1], $handler->get(['count']));
		$handler->set(['count' => 2, 'added' => false]);
		$this->assertSame(['old' => 'value', 'count' => 2, 'added' => false], $handler->get());
		$this->assertSame(['depending_services' => 'Ok'], $handler->checkDependencies());
	}
}
