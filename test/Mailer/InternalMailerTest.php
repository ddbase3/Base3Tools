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

use Base3\Base3Tools\Mailer\Internal\InternalMailer;
use PHPUnit\Framework\TestCase;

final class InternalMailerTest extends TestCase {

	public function testConfigurationMethodsAreNoOps(): void {
		$mailer = new InternalMailer();
		$this->assertNull($mailer->isMail());
		$this->assertNull($mailer->isSmtp());
		$this->assertNull($mailer->setSmtpAuth(true));
		$this->assertNull($mailer->setHost('localhost'));
		$this->assertNull($mailer->setPort(2525));
		$this->assertNull($mailer->setCredentials('user', 'password'));
	}
}
