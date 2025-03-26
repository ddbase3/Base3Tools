<?php declare(strict_types=1);

namespace Base3Tools\Notification\Api;

use Base3\Api\IOutput;

interface INotification {

	public function send($user, $message, $url);

}
