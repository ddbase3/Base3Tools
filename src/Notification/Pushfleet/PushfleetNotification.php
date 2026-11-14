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

namespace Base3\Base3Tools\Notification\Pushfleet;

use Base3\Base3Tools\Notification\Api\INotification;

class PushfleetNotification implements INotification {

	public function send($user, $message, $url) {

/*
$curl = curl_init();
curl_setopt_array($curl, array(
	CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_URL => "https://pushfleet.com/api/v1/send?appid=A1111111&userid=U1111111,U2222222&message=" . urlencode((string) $message) . "&url=" . urlencode($url)
));
$resp = curl_exec($curl);
curl_close($curl);
*/

// https://pushfleet.com/api/v1/send?appid=A1111111&userid=U1111111,U2222222&message=test&url=test.com


	}

}
