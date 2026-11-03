<?php declare(strict_types=1);

namespace Base3Tools\Test;

use Base3\Api\IContainer;
use Base3Tools\Base3ToolsPlugin;
use PHPUnit\Framework\TestCase;

final class Base3ToolsPluginTest extends TestCase {

	public function testRegistrationUsesTheExistingContainerAndSharedFlag(): void {
		$container = $this->createMock(IContainer::class);
		$container->expects($this->once())->method('set')
			->with('base3toolsplugin', $this->isInstanceOf(Base3ToolsPlugin::class), IContainer::SHARED)
			->willReturnSelf();

		$plugin = new Base3ToolsPlugin($container);
		$this->assertSame('base3toolsplugin', $plugin::getName());
		$plugin->init();
	}
}
