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

use Base3\Base3Tools\Mailer\Mail;
use PHPUnit\Framework\TestCase;

final class MailTest extends TestCase {

	public function testDefaultsAreCompleteAndIndependent(): void {
		$first = new Mail();
		$second = new Mail();
		$this->assertSame([
			'from' => null, 'replyto' => null, 'to' => [], 'cc' => [], 'bcc' => [],
			'subject' => '', 'text' => '', 'html' => '',
			'attachmentstring' => [], 'attachmentfile' => [],
			'embeddedstring' => [], 'embeddedfile' => []
		], $first->getData());
		$first->addTo('one@example.org');
		$this->assertSame([], $second->getData()['to']);
	}

	public function testEveryFieldIsStoredWithOptionalNamesAndFilenames(): void {
		$mail = new Mail();
		$mail->setFrom('sender@example.org', 'Sender');
		$mail->setReplyTo('reply@example.org');
		$mail->addTo('one@example.org', 'One');
		$mail->addTo('two@example.org');
		$mail->addCc('cc@example.org');
		$mail->addBcc('bcc@example.org', 'Blind');
		$mail->setSubject('Subject');
		$mail->setText('Plain');
		$mail->setHtml('<p>HTML</p>');
		$mail->addAttachmentString('binary', 'data.bin');
		$mail->addAttachmentFile('/tmp/file.pdf');
		$mail->addAttachmentFile('/tmp/file2.pdf', 'renamed.pdf');
		$mail->addEmbeddedImageString('png', 'logo');
		$mail->addEmbeddedImageFile('/tmp/logo.png', 'img', 'image.png');
		$data = $mail->getData();

		$this->assertSame(['email' => 'sender@example.org', 'name' => 'Sender'], $data['from']);
		$this->assertSame(['email' => 'reply@example.org', 'name' => null], $data['replyto']);
		$this->assertCount(2, $data['to']);
		$this->assertSame(['email' => 'two@example.org', 'name' => null], $data['to'][1]);
		$this->assertSame(['email' => 'cc@example.org', 'name' => null], $data['cc'][0]);
		$this->assertSame(['email' => 'bcc@example.org', 'name' => 'Blind'], $data['bcc'][0]);
		$this->assertSame('Subject', $data['subject']);
		$this->assertSame('Plain', $data['text']);
		$this->assertSame('<p>HTML</p>', $data['html']);
		$this->assertSame(['string' => 'binary', 'filename' => 'data.bin'], $data['attachmentstring'][0]);
		$this->assertSame(['path' => '/tmp/file.pdf', 'filename' => null], $data['attachmentfile'][0]);
		$this->assertSame(['path' => '/tmp/file2.pdf', 'filename' => 'renamed.pdf'], $data['attachmentfile'][1]);
		$this->assertSame(['string' => 'png', 'cid' => 'logo', 'filename' => null], $data['embeddedstring'][0]);
		$this->assertSame(['path' => '/tmp/logo.png', 'cid' => 'img', 'filename' => 'image.png'], $data['embeddedfile'][0]);
	}
}
