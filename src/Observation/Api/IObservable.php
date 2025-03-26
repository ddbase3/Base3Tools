<?php declare(strict_types=1);

namespace Base3Tools\Observation\Api;

use Base3\Api\IBase;

interface IObservable extends IBase {

	public function addObserver($observer);
	public function removeObserver($observer);
	// protected function notifyObservers($notificationType);

}
