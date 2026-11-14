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

interface IMail {

	public function setFrom($email, $name = null);
	public function setReplyTo($email, $name = null);

	public function addTo($email, $name = null);
	public function addCc($email, $name = null);
	public function addBcc($email, $name = null);

	public function setSubject($subject);

	public function setText($text);
	public function setHtml($html);

	public function addAttachmentString($string, $filename);
	public function addAttachmentFile($path, $filename = null);

	public function addEmbeddedImageString($string, $cid, $filename = null);
	public function addEmbeddedImageFile($path, $cid, $filename = null);

	public function getData();

}
