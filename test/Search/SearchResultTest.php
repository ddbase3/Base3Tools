<?php declare(strict_types=1);

namespace Base3Tools\Test\Search;

use Base3Tools\Search\SearchResult;
use PHPUnit\Framework\TestCase;

final class SearchResultTest extends TestCase {

	public function testFieldsRemainIndependentMutableValues(): void {
		$result = new SearchResult();
		$this->assertNull($result->l0);
		$this->assertNull($result->l1);
		$this->assertNull($result->ty);
		$this->assertNull($result->sv);
		$this->assertNull($result->qu);
		$this->assertNull($result->fn);
		$this->assertNull($result->da);
		$result->l0 = 'Title';
		$result->l1 = 'Description';
		$result->ty = 'document';
		$result->sv = 'search';
		$result->qu = 0.75;
		$result->fn = 'open';
		$result->da = ['id' => 42];
		$this->assertSame(['Title', 'Description', 'document', 'search', 0.75, 'open', ['id' => 42]],
			[$result->l0, $result->l1, $result->ty, $result->sv, $result->qu, $result->fn, $result->da]);
	}
}
