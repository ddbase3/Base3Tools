<?php declare(strict_types=1);

namespace Base3Tools\Test\Util;

use Base3Tools\Util\DeviceDetect\DeviceDetect;
use PHPUnit\Framework\TestCase;

final class DeviceDetectBehaviorTest extends TestCase {

	private mixed $previousUserAgent;

	protected function setUp(): void {
		$this->previousUserAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
	}

	protected function tearDown(): void {
		if ($this->previousUserAgent === null) {
			unset($_SERVER['HTTP_USER_AGENT']);
		} else {
			$_SERVER['HTTP_USER_AGENT'] = $this->previousUserAgent;
		}
	}

	public function testKnownDeviceModelsTakePrecedenceOverGenericOperatingSystems(): void {
		$detect = new DeviceDetect();
		$_SERVER['HTTP_USER_AGENT'] = 'Android (SM-G950F) Linux';
		$this->assertSame('Samsung Galaxy S8', $detect->getDevice());
		$_SERVER['HTTP_USER_AGENT'] = 'Windows NT 10.0; Win64';
		$this->assertSame('Windows 10 PC', $detect->getDevice());
		$_SERVER['HTTP_USER_AGENT'] = 'iPad; CPU OS 15_0';
		$this->assertSame('iPad', $detect->getDevice());
	}

	public function testUnknownAgentIsReturnedAsDescription(): void {
		$_SERVER['HTTP_USER_AGENT'] = 'unrecognised-device/7.0';
		$this->assertSame('Unknown device (unrecognised-device/7.0)', (new DeviceDetect())->getDevice());
	}
}
