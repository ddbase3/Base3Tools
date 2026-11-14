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

namespace Base3\Base3Tools\Search\Custom;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\Base3Tools\Search\Api\ISearchProvider;
use Base3\Base3Tools\Search\Api\ISearchService;

class CustomSearchProvider implements ISearchProvider {

	private $classmap;

	public function __construct(IClassMap $classmap) {
		$this->classmap = $classmap;
	}

	// Implementation of IBase

	public static function getName(): string {
		return "customsearchprovider";
	}

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {
		$result = array();

		$q = $_REQUEST["q"];

		$searchservices = $this->classmap->getInstancesByInterface(ISearchService::class);
		foreach ($searchservices as $searchservice)
			$result = array_merge($result, $searchservice->search($q));

		return json_encode($result);
	}

	public function getHelp(): string {
		return 'Help of CustomSearchProvider' . "\n";
	}
}
