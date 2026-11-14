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

namespace Base3\Base3Tools\Mailer\Internal;

use Base3\Base3Tools\Mailer\Api\IMailer;

class InternalMailer implements IMailer {

	// Implementation of IMailer

	public function isMail() { /* not needed */ }

	public function isSmtp() { /* not used */ }
	public function setSmtpAuth($smtpauth) { /* not used */ }

	public function setHost($host) { /* not used */ }
	public function setPort($port = 25) { /* not used */ }
	public function setCredentials($user, $pass) { /* not used */ }

	/* send an IMail object */
	public function send($mail) {
		$data = $mail->getData();
		mail(implode(", ", $data["to"]), $data["subject"], $data["text"]);
	}

}
