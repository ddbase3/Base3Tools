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

namespace Base3\Base3Tools\Test;

use Base3\CoreFoundation\Api\IContainer;
use Base3\Base3Tools\Base3ToolsPlugin;
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
