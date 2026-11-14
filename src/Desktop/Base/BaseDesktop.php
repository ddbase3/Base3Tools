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

namespace Base3\Base3Tools\Desktop\Base;

use Base3\Core\ServiceLocator;
use Base3\Base3Tools\Desktop\Api\IDesktop;
use Base3\CoreFoundation\Api\ICheck;

class BaseDesktop implements IDesktop, ICheck {

	private $servicelocator;
	private $database;

	public function __construct() {
		$this->servicelocator = ServiceLocator::getInstance();
		$this->database = $this->servicelocator->get('database');
	}

	// Implementation of IBase

	public static function getName(): string {
		return "basedesktop";
	}

	// Implementation of IDesktop

	public function addTask($tasktype, $taskdata, $users) {
		$this->database->connect();
		$uuid = $this->uuid();
		$sql = "INSERT INTO `task` (`id`, `type`, `data`, `created`)
			VALUES (0x" . $uuid . ", '" . $this->database->escape($tasktype) . "', '" . $this->database->escape(json_encode($taskdata)) . "', NOW())";
		$this->database->nonQuery($sql);
		if ($users && sizeof($users)) {
			$sql = "INSERT INTO `taskuser` (`taskid`, `userid`) VALUES ";
			foreach ($users as $user) $sql .= "(0x" . $uuid . ", '" . $this->database->escape($user) . "'), ";
			$sql = substr($sql, 0, -2);
			$this->database->nonQuery($sql);
		}
		return $uuid;
	}

	public function numTasks($tasktype) {
		$this->database->connect();
		$sql = "SELECT COUNT(`id`) FROM `task` WHERE `type` = '" . $this->database->escape($tasktype) . "'";
		$num = $this->database->scalarQuery($sql);
		return intval($num);
	}

	public function waitingTasks($tasktype) {
		$this->database->connect();
		$sql = "SELECT COUNT(`id`) FROM `task` WHERE `type` = '" . $this->database->escape($tasktype) . "' AND `finished` LIKE '0000%'";
		$num = $this->database->scalarQuery($sql);
		return intval($num);
	}

	public function removeTask($taskid) {
		// User werden automatisch gelöscht wegen Foreign Key - Cascade
		$this->database->connect();
		$sql = "DELETE FROM `task` WHERE `id` = 0x" . $this->database->escape($taskid);
		$this->database->nonQuery($sql);
	}


	public function removeTasks($tasktype) {
		// User werden automatisch gelöscht wegen Foreign Key - Cascade
		$this->database->connect();
		$sql = "DELETE FROM `task` WHERE `type` = '" . $this->database->escape($tasktype) . "'";
		$this->database->nonQuery($sql);
	}


	public function getResults($tasktype) {
		$results = array();
		$this->database->connect();
		$sql = "SELECT LOWER(HEX(`id`)) AS `id`, `result` FROM `task` WHERE `type` = '" . $this->database->escape($tasktype) . "' AND `finished` > '1' AND `finished` < DATE_ADD(NOW(), INTERVAL -1 MINUTE)";
		$res = $this->database->multiQuery($sql);
		foreach ($res as $row) $results[$row["id"]] = json_decode($row["result"]);
		return $results;
	}

	// Implementation of ICheck

	public function checkDependencies() {
		return array(
			"depending_services" => $this->database == null ? "Fail" : "Ok"
		);
	}

	// Private methods

	private function uuid() {
		return md5((string)microtime(true));
	}

}
