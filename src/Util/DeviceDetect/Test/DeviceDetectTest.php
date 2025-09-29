<?php declare(strict_types=1);

namespace Base3Tools\Util\DeviceDetect\Test;

use Base3\Page\Api\IPage;
use Base3Tools\Util\DeviceDetect\DeviceDetect;

class DeviceDetectTest implements IPage {

	// Implementation of IBase

	public static function getName(): string {
		return "devicedetecttest";
	}

	// Implementation of IPage

        public function getUrl() {
                return $this->getName() . ".php";
        }

	// Implementation of IOutput

	public function getOutput($out = "html") {
		$str = '<h1>DeviceDetectTest</h1>';
		$dd = new DeviceDetect;
		$str .= $dd->getDevice();
		return $str;
	}

	public function getHelp() {
		return 'Help of DeviceDetectTest' . "\n";
	}

}
