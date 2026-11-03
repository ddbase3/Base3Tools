<?php declare(strict_types=1);

namespace Base3Tools\Test\Search;

use Base3\Api\IClassMap;
use Base3Tools\Search\Api\ISearchService;
use Base3Tools\Search\Custom\CustomSearchProvider;
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
