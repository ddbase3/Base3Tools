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

namespace Base3\Base3Tools\Test\Search;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\Base3Tools\Search\Api\ISearchService;
use Base3\Base3Tools\Search\Custom\CustomSearchProvider;
use PHPUnit\Framework\TestCase;

final class CustomSearchProviderTest extends TestCase {

	private array $previousRequest;

	protected function setUp(): void {
		$this->previousRequest = $_REQUEST;
	}

	protected function tearDown(): void {
		$_REQUEST = $this->previousRequest;
	}

	public function testAggregatesResultsFromServicesInTheirDiscoveryOrder(): void {
		$_REQUEST['q'] = 'cat';
		$first = $this->createMock(ISearchService::class);
		$first->expects($this->once())->method('search')->with('cat')->willReturn([['title' => 'first']]);
		$second = $this->createMock(ISearchService::class);
		$second->expects($this->once())->method('search')->with('cat')->willReturn([['title' => 'second']]);
		$map = $this->createMock(IClassMap::class);
		$map->expects($this->once())->method('getInstancesByInterface')->with(ISearchService::class)->willReturn([$first, $second]);

		$provider = new CustomSearchProvider($map);
		$this->assertSame('customsearchprovider', $provider::getName());
		$this->assertSame([['title' => 'first'], ['title' => 'second']], json_decode($provider->getOutput(), true));
		$this->assertStringContainsString('CustomSearchProvider', $provider->getHelp());
	}

	public function testNoServicesReturnsEmptyJsonArray(): void {
		$_REQUEST['q'] = '';
		$map = $this->createStub(IClassMap::class);
		$map->method('getInstancesByInterface')->willReturn([]);
		$this->assertSame('[]', (new CustomSearchProvider($map))->getOutput('json', true));
	}
}
