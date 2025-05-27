<?php declare(strict_types=1);

namespace Base3Tools;

use Base3\Api\IPlugin;
use Base3\Api\IContainer;

class Base3ToolsPlugin implements IPlugin {

        private $container;

        public function __construct(IContainer $container) {
                $this->container = $container;
        }

	// Implementation of IBase

	public static function getName(): string {
		return "base3toolsplugin";
	}

	// Implementation of IPlugin

	public function init() {

		$this->container

			->set($this->getName(), $this, IContainer::SHARED);
	}

}
