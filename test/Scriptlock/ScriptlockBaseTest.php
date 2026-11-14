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

namespace Base3\Base3Tools\Test\Scriptlock;

use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\Base3Tools\Scriptlock\Base\ScriptlockBase;
use PHPUnit\Framework\TestCase;

final class ScriptlockBaseTest extends TestCase {

	private ?array $previousServer = null;

	protected function setUp(): void {
		$this->previousServer = $_SERVER ?? null;
		$_SERVER = $this->previousServer ?? [];
		unset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	}

	protected function tearDown(): void {
		if ($this->previousServer === null) {
			unset($_SERVER);
			return;
		}
		$_SERVER = $this->previousServer;
	}

	public function testEmptyConfigurationDoesNotLock(): void {
		$this->assertFalse($this->lock()->check());
	}

	public function testRemoteAddressPrefixAndUnmatchedAddress(): void {
		$lock = $this->lock();
		(new \ReflectionProperty($lock, 'cnf'))->setValue($lock, ['declineremoteaddr' => ['192.0.2.']]);
		$_SERVER['REMOTE_ADDR'] = '192.0.2.17';
		$this->assertTrue($lock->check());
		$_SERVER['REMOTE_ADDR'] = '198.51.100.9';
		$this->assertFalse($lock->check());
	}

	public function testLanguagePrefixAndMissingLanguageDoNotCauseProcessExit(): void {
		$lock = $this->lock();
		(new \ReflectionProperty($lock, 'cnf'))->setValue($lock, ['declineacceptlanguage' => ['de']]);
		$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de-DE,de;q=0.9,en-US;q=0.8';
		$this->assertTrue($lock->check());
		$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'fr-FR,en-US;q=0.8';
		$this->assertFalse($lock->check());
	}

	public function testUnexpectedRuleTypesAreIgnored(): void {
		$lock = $this->lock();
		$_SERVER['REMOTE_ADDR'] = '192.0.2.1';
		$_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de-DE';
		(new \ReflectionProperty($lock, 'cnf'))->setValue($lock, ['declineremoteaddr' => '192.', 'declineacceptlanguage' => 'de']);
		$this->assertFalse($lock->check());
	}

	private function lock(): ScriptlockBase {
		$configuration = $this->createStub(IConfiguration::class);
		$configuration->method('get')->with('scriptlock')->willReturn([]);
		return new ScriptlockBase($configuration);
	}
}
