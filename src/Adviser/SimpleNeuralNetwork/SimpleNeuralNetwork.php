<?php declare(strict_types=1);

namespace Base3Tools\Adviser\SimpleNeuralNetwork;

use Base3\Core\ServiceLocator;
use Base3Tools\Adviser\Api\IAdviser;

class SimpleNeuralNetwork implements IAdviser {

	private $servicelocator;
	private $database;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
		$this->database = $this->servicelocator->get('database');
	}

	// Implementation of IBase

	public function getName() {
		return "simpleneuralnetwork";
	}

}
