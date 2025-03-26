<?php declare(strict_types=1);

namespace Base3Tools;

use Base3\Api\IPlugin;
use Base3\Core\ServiceLocator;

class Base3ToolsPlugin implements IPlugin {

	private $servicelocator;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
	}

	// Implementation of IBase

	public function getName() {
		return "base3toolsplugin";
	}

	// Implementation of IPlugin

	public function init() {

		$this->servicelocator

			->set(
				$this->getName(),
				$this,
				ServiceLocator::SHARED);
	}

}
