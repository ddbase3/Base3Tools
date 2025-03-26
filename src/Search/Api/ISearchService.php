<?php declare(strict_types=1);

namespace Base3Tools\Search\Api;

use Base3\Api\IBase;

interface ISearchService extends IBase {

	public function search($q);

}
