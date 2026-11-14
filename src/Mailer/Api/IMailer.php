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

namespace Base3\Base3Tools\Mailer\Api;

interface IMailer {

	public function isMail();

	public function isSmtp();
	public function setSmtpAuth($smtpauth);

	public function setHost($host);
	public function setPort($port = 25);
	public function setCredentials($user, $pass);

	/* send an IMail object */
	public function send($mail);

}
