<?php declare(strict_types=1);

namespace Base3Tools\Test\Status;

use Base3\Session\Api\ISession;
use Base3Tools\Status\Session\SessionStatusHandler;
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
