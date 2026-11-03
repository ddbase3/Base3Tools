<?php declare(strict_types=1);

namespace Base3Tools\Test\Mailer;

use Base3Tools\Mailer\Internal\InternalMailer;
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
