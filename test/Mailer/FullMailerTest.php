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

namespace Base3\Base3Tools\Test\Mailer;

use Base3\Core\ServiceLocator;
use Base3\CoreFoundation\Configuration\Api\IConfiguration;
use Base3\Base3Tools\Mailer\Full\FullMailer;
use PHPUnit\Framework\TestCase;

final class FullMailerTest extends TestCase {

	private ServiceLocator $previousLocator;

	protected function setUp(): void {
		$this->previousLocator = ServiceLocator::getInstance();
		ServiceLocator::useInstance(new ServiceLocator());
	}

	protected function tearDown(): void {
		ServiceLocator::useInstance($this->previousLocator);
	}

	public function testExplicitSettingsAndSetterMethodsUpdateMailerConfiguration(): void {
		$mailer = new FullMailer(['mailer' => 'mail', 'smtpauth' => false, 'host' => 'mail.example.org', 'port' => 2525, 'user' => 'a', 'pass' => 'b']);
		$this->assertSame('mail', $this->value($mailer, 'mailer'));
		$this->assertFalse($this->value($mailer, 'smtpauth'));
		$this->assertSame('mail.example.org', $this->value($mailer, 'host'));
		$this->assertSame(2525, $this->value($mailer, 'port'));
		$mailer->isSmtp();
		$mailer->setSmtpAuth('0');
		$mailer->setHost('smtp.example.org');
		$mailer->setPort(587);
		$mailer->setCredentials('user', 'secret');
		$this->assertSame('smtp', $this->value($mailer, 'mailer'));
		$this->assertFalse($this->value($mailer, 'smtpauth'));
		$this->assertSame('smtp.example.org', $this->value($mailer, 'host'));
		$this->assertSame(587, $this->value($mailer, 'port'));
		$this->assertSame('user', $this->value($mailer, 'user'));
		$this->assertSame('secret', $this->value($mailer, 'pass'));
		$mailer->isMail();
		$mailer->setSmtpAuth(true);
		$mailer->setPort();
		$this->assertSame('mail', $this->value($mailer, 'mailer'));
		$this->assertTrue($this->value($mailer, 'smtpauth'));
		$this->assertSame(25, $this->value($mailer, 'port'));
	}

	public function testConfigurationCanBeReadFromExistingServiceLocator(): void {
		$configuration = $this->createMock(IConfiguration::class);
		$configuration->expects($this->once())->method('get')->with('mailer')->willReturn(['host' => 'configured.example.org']);
		$locator = new ServiceLocator();
		$locator->set('configuration', $configuration, ServiceLocator::SHARED);
		ServiceLocator::useInstance($locator);

		$mailer = new FullMailer();
		$this->assertSame('configured.example.org', $this->value($mailer, 'host'));
		$this->assertSame(['depending_services' => 'Ok'], $mailer->checkDependencies());
	}

	public function testInvalidAndMissingConfigKeepDefaultsAndReportMissingDependency(): void {
		$mailer = new FullMailer('invalid');
		$this->assertSame('localhost', $this->value($mailer, 'host'));
		$this->assertSame(['depending_services' => 'Fail'], $mailer->checkDependencies());
	}

	private function value(FullMailer $mailer, string $property): mixed {
		return (new \ReflectionProperty($mailer, $property))->getValue($mailer);
	}
}
