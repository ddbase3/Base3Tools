<?php declare(strict_types=1);

namespace Base3Tools\Observation\Api;

use Api\IBase;

interface IObserver extends IBase {

	public function notify($notificationType = null, $notificationObject = null);

}
