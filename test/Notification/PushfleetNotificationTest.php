<?php declare(strict_types=1);

namespace Base3Tools\Test\Notification;

use Base3Tools\Notification\Pushfleet\PushfleetNotification;
use PHPUnit\Framework\TestCase;

final class PushfleetNotificationTest extends TestCase {

	public function testInactiveDeliveryMethodDoesNotPerformAnExternalAction(): void {
		$this->assertNull((new PushfleetNotification())->send('user', 'message', 'https://example.org'));
	}
}
