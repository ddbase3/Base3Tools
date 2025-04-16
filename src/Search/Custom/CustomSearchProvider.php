<?php declare(strict_types=1);

namespace Base3Tools\Search\Custom;

use Base3\Api\IClassMap;
use Base3Tools\Search\Api\ISearchProvider;
use Base3Tools\Search\Api\ISearchService;

class CustomSearchProvider implements ISearchProvider {

	private $classmap;

	public function __construct(IClassMap $classmap) {
		$this->classmap = $classmap;
	}

	// Implementation of IBase

	public function getName() {
		return "customsearchprovider";
	}

	// Implementation of IOutput

	public function getOutput($out = "html") {
		$result = array();

		$q = $_REQUEST["q"];

		$searchservices = $this->classmap->getInstancesByInterface(ISearchService::class);
		foreach ($searchservices as $searchservice)
			$result = array_merge($result, $searchservice->search($q));

		return json_encode($result);
	}

	public function getHelp() {
		return 'Help of CustomSearchProvider' . "\n";
	}

}
