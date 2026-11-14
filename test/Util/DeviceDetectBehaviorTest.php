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

namespace Base3\Base3Tools\Test\Util;

use Base3\Base3Tools\Util\DeviceDetect\DeviceDetect;
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
