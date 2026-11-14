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

namespace Base3\Base3Tools\Observation\Base;

use Base3\CoreFoundation\Api\IClassMap;
use Base3\Base3Tools\Observation\Api\IObservation;

class Observation implements IObservation {

	private $classmap;

	public function __construct(IClassMap $classmap) {
		$this->classmap = $classmap;
	}

	// Implementation of IObservation

	public function addObserver($name, $observer) {
		$data = $this->getData($name);
		$data[] = $observer->getName();
		$result = array_unique($data);
		$this->setData($name, $result);
	}

	public function removeObserver($name, $observer) {
		$data = $this->getData($name);
		$result = array_diff($data, array($observer->getName()));
		$this->setData($name, $result);
	}

	public function notifyObservers($name, $notificationType = null, $notificationObject = null) {
		$data = $this->getData($name);
		foreach ($data as $name) {
			$observer = $this->classmap->getInstanceByInterfaceName(\Base3\Base3Tools\Observation\Api\IObserver::class, $name);
			if ($observer != null) $observer->notify($notificationType, $notificationObject);
		}
	}

	// private functions

	private function getData($name) {
		$filename = DIR_LOCAL . "Observation" . DIRECTORY_SEPARATOR . $name . ".json";
		if (!file_exists($filename)) return array();
		$json = file_get_contents($filename);
		return json_decode($json, true);
	}

	private function setData($name, $data) {
		$filename = DIR_LOCAL . "Observation" . DIRECTORY_SEPARATOR . $name . ".json";
		if (!sizeof($data) && file_exists($filename)) {
			unlink($filename);
			return;
		}
		$fp = fopen($filename, "w");
		fwrite($fp, json_encode($data));
		fclose($fp);
	}

}
