<?php declare(strict_types=1);

namespace Base3Tools\Util\MobileDetect\Test;

use Base3\Page\Api\IPage;
use Base3Tools\Util\MobileDetect\MobileDetect;

class MobileDetectTest implements IPage {

	// Implementation of IBase

	public static function getName(): string {
		return "mobiledetecttest";
	}

	// Implementation of IPage

        public function getUrl() {
                return $this->getName() . ".php";
        }

	// Implementation of IOutput

	public function getOutput(string $out = 'html', bool $final = false): string {
		$str = '<h1>MobileDetectTest</h1>';

		$md = new MobileDetect;
		$str .= '<p>mobile? - ' . ( $md->isMobile() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>tablet? - ' . ( $md->isTablet() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>phone? - ' . ( $md->isMobile() && !$md->isTablet() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>ios? - ' . ( $md->isiOS() ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>android? - ' . ( $md->isAndroidOS() ? 'yes' : 'no' ) . ' - ' . $md->version('Android') . '</p>';
		$str .= '<p>chrome? - ' . ( $md->is('Chrome') ? 'yes' : 'no' ) . '</p>';
		$str .= '<p>samsung? - ' . ( $md->isSamsung() ? 'yes' : 'no' ) . '</p>';
		$str .= '';  // version(Windows NT)

		return $str;
	}

	public function getHelp(): string {
		return 'Help of MobileDetectTest' . "\n";
	}
}
