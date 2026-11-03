<?php declare(strict_types=1);

namespace Base3Tools\Test\Observation;

use Base3Tools\Observation\Api\IObserver;

final class ObserverSpy implements IObserver {

	public array $notifications = [];

	public static function getName(): string {
		return 'observerspy';
	}

	public function notify($notificationType = null, $notificationObject = null) {
		$this->notifications[] = [$notificationType, $notificationObject];
	}
}
