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

use Base3\Base3Tools\Status\Api\IStatusHandler;
use Base3\Base3Tools\Status\File\FileStatusHandler;
use Base3\Base3Tools\Status\StatusHandlerProxy;
use PHPUnit\Framework\TestCase;

final class StatusHandlerProxyTest extends TestCase {

	public function testProxyForwardsReadAndWriteWithoutChangingArguments(): void {
		$connector = $this->createMock(IStatusHandler::class);
		$connector->expects($this->once())->method('get')->with(['id'])->willReturn(['id' => 3]);
		$connector->expects($this->once())->method('set')->with(['id' => 3]);
		$proxy = new StatusHandlerProxy($connector);

		$this->assertSame(['id' => 3], $proxy->get(['id']));
		$proxy->set(['id' => 3]);
		$this->assertSame([], $proxy->checkDependencies());
	}

	public function testCheckableConnectorExposesItsOwnDependencyResult(): void {
		$connector = $this->createMock(FileStatusHandler::class);
		$connector->expects($this->once())->method('checkDependencies')->willReturn(['depending_services' => 'Ok']);
		$proxy = new StatusHandlerProxy($connector);
		$this->assertSame(['depending_services' => 'Ok'], $proxy->checkDependencies());
	}
}
