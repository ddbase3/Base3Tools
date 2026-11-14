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

use Base3\Base3Tools\Search\SearchResult;
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
