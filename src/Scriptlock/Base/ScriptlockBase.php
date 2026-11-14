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

namespace Base3\Base3Tools\Scriptlock\Base;

use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\Base3Tools\Scriptlock\Api\IScriptlock;

class ScriptlockBase implements IScriptlock {

	private $cnf = array();

	public function __construct(IConfiguration $configuration) {

		$cnf = $configuration->get('scriptlock');

		if ($cnf != null) $this->cnf = $cnf;
		if ($this->check()) $this->lock();
	}

	// Implementation of IScriptlock

	public function check() {
		if ($this->cnf == null) return false;
		if ($this->checkRemoteAddr()) return true;
		if ($this->checkAcceptLanguage()) return true;
		return false;
	}

	public function lock() {
		exit;
	}

	// private methods

	private function checkRemoteAddr() {
		if (!isset($_SERVER["REMOTE_ADDR"]) || !isset($this->cnf["declineremoteaddr"]) || !is_array($this->cnf["declineremoteaddr"])) return false;
		foreach ($this->cnf["declineremoteaddr"] as $ip) {
			$len = strlen($ip);
			if (substr($_SERVER["REMOTE_ADDR"], 0, $len) == $ip) return true;
		}
		return false;
	}

	private function checkAcceptLanguage() {
		if (!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) return false;
		if (!isset($this->cnf["declineacceptlanguage"]) || !is_array($this->cnf["declineacceptlanguage"])) return false;
		$langsstr = str_replace(";", ",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]);
		$languages = explode(",", $langsstr);
		$remotelangs = array();
		foreach ($languages as $l) {
			if (strpos($l, "=") !== false) continue;
			if (strpos($l, "-") !== false) {
				$remotelangs[] = substr($l, 0, strpos($l, "-"));
				continue;
			}
			if (strpos($l, "-") !== false) {
				$remotelangs[] = $l;
				continue;
			}
		}
		foreach ($remotelangs as $l)
			if (in_array($l, $this->cnf["declineacceptlanguage"])) return true;
		return false;
	}

}
