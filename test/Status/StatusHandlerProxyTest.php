<?php declare(strict_types=1);

namespace Base3Tools\Test\Status;

use Base3Tools\Status\Api\IStatusHandler;
use Base3Tools\Status\File\FileStatusHandler;
use Base3Tools\Status\StatusHandlerProxy;
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
