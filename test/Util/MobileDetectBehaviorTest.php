<?php declare(strict_types=1);

namespace Base3Tools\Test\Util;

use Base3Tools\Util\MobileDetect\MobileDetect;
use PHPUnit\Framework\TestCase;

final class MobileDetectBehaviorTest extends TestCase {

	private const IPHONE = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1';
	private const IPAD = 'Mozilla/5.0 (iPad; CPU OS 14_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1';
	private const DESKTOP = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36';

	private array $previousServer;

	protected function setUp(): void {
		$this->previousServer = $_SERVER;
		$_SERVER = [];
	}

	protected function tearDown(): void {
		$_SERVER = $this->previousServer;
	}

	public function testMissingUserAgentDoesNotThrowAndCanBeSetLater(): void {
		$detect = new MobileDetect([]);
		$this->assertNull($detect->getUserAgent());
		$this->assertFalse($detect->isMobile());
		$this->assertFalse($detect->isTablet());
		$this->assertFalse($detect->is('iPhone'));
		$this->assertFalse($detect->isiPhone());
		$this->assertFalse($detect->match('iPhone'));
		$this->assertFalse($detect->version('Chrome'));
		$this->assertSame(MobileDetect::MOBILE_GRADE_C, $detect->mobileGrade());

		$this->assertTrue($detect->match('iPhone', self::IPHONE));
		$detect->setUserAgent(self::IPHONE);
		$this->assertTrue($detect->isMobile());
		$this->assertSame('17_0', $detect->version('iPhone'));
		$detect->setUserAgent(null);
		$this->assertNull($detect->getUserAgent());
		$this->assertFalse($detect->isMobile());
		$this->assertFalse($detect->version('iPhone'));
	}

	public function testMobileHeaderStillWorksWithoutUserAgent(): void {
		$detect = new MobileDetect(['HTTP_ACCEPT' => 'application/vnd.wap.xhtml+xml']);
		$this->assertNull($detect->getUserAgent());
		$this->assertTrue($detect->isMobile());
		$this->assertFalse($detect->isTablet());
		$this->assertFalse($detect->version('Android'));

		$wap = new MobileDetect(['HTTP_X_WAP_PROFILE' => 'mobile-profile']);
		$this->assertNull($wap->getUserAgent());
		$this->assertTrue($wap->isMobile());
	}

	public function testPhoneTabletAndDesktopAgents(): void {
		$iphone = new MobileDetect([], self::IPHONE);
		$this->assertTrue($iphone->isMobile());
		$this->assertFalse($iphone->isTablet());
		$this->assertTrue($iphone->is('iPhone'));
		$this->assertTrue($iphone->isiPhone());
		$this->assertFalse($iphone->is('NotADevice'));
		$this->assertTrue($iphone->match('iPhone'));
		$this->assertSame('iPhone', $iphone->getMatchingRegex());
		$this->assertNotEmpty($iphone->getMatchesArray());

		$ipad = new MobileDetect([], self::IPAD);
		$this->assertTrue($ipad->isMobile());
		$this->assertTrue($ipad->isTablet());

		$desktop = new MobileDetect([], self::DESKTOP);
		$this->assertFalse($desktop->isMobile());
		$this->assertFalse($desktop->isTablet());
		$this->assertFalse($desktop->is('Chrome'));
		$this->assertSame('120.0.0.0', $desktop->version('Chrome'));
		$this->assertFalse($desktop->match('iPhone'));
	}

	public function testHeaderNormalizationAndUserAgentExtraction(): void {
		$detect = new MobileDetect([
			'HTTP_USER_AGENT' => self::IPHONE,
			'HTTP_X_DEVICE_USER_AGENT' => 'secondary',
			'SERVER_NAME' => 'ignored'
		]);
		$this->assertSame(self::IPHONE, $detect->getHttpHeader('User-Agent'));
		$this->assertSame(self::IPHONE, $detect->getHttpHeader('HTTP_USER_AGENT'));
		$this->assertNull($detect->getHttpHeader('X-Unset'));
		$this->assertArrayNotHasKey('SERVER_NAME', $detect->getHttpHeaders());
		$this->assertStringContainsString(self::IPHONE, (string)$detect->getUserAgent());
		$this->assertNotEmpty($detect->getUaHttpHeaders());
	}

	public function testSetUserAgentTrimsAndLimitsToFiveHundredCharacters(): void {
		$detect = new MobileDetect([], 'initial');
		$detect->setUserAgent('  ' . str_repeat('x', 600) . '   ');
		$this->assertSame(str_repeat('x', 500), $detect->getUserAgent());
		$this->assertFalse($detect->is('iPhone'));
		$detect->setUserAgent(self::IPHONE);
		$this->assertTrue($detect->is('iPhone'));
	}

	public function testCloudFrontCanSupplyMobileOrTabletClassification(): void {
		$mobile = new MobileDetect(['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER' => 'true']);
		$this->assertSame('Amazon CloudFront', $mobile->getUserAgent());
		$this->assertTrue($mobile->isMobile());
		$this->assertFalse($mobile->isTablet());
		$this->assertSame(['HTTP_CLOUDFRONT_IS_MOBILE_VIEWER' => 'true'], $mobile->getCfHeaders());

		$tablet = new MobileDetect(['HTTP_CLOUDFRONT_IS_TABLET_VIEWER' => 'true']);
		$this->assertTrue($tablet->isTablet());
		$this->assertFalse($tablet->isMobile());
		$this->assertFalse($tablet->setCfHeaders(['OTHER' => 'no']));
		$this->assertSame([], $tablet->getCfHeaders());
	}

	public function testMobileSpecificHeadersWithoutMatchingValueAreIgnored(): void {
		$detect = new MobileDetect([], self::DESKTOP);
		$this->assertFalse($detect->checkHttpHeadersForMobile());
		$detect->setHttpHeaders(['HTTP_ACCEPT' => 'text/html']);
		$this->assertFalse($detect->checkHttpHeadersForMobile());
		$detect->setHttpHeaders(['HTTP_ACCEPT' => 'application/vnd.wap.xhtml+xml']);
		$this->assertTrue($detect->checkHttpHeadersForMobile());
	}

	public function testRulesAndVersionsAreAvailableWithoutGlobalState(): void {
		$detect = new MobileDetect([], self::IPHONE);
		$this->assertSame(MobileDetect::VERSION, MobileDetect::getScriptVersion());
		$this->assertNotEmpty(MobileDetect::getPhoneDevices());
		$this->assertNotEmpty(MobileDetect::getTabletDevices());
		$this->assertNotEmpty(MobileDetect::getOperatingSystems());
		$this->assertSame(MobileDetect::getBrowsers(), MobileDetect::getUserAgents());
		$this->assertNotEmpty(MobileDetect::getUtilities());
		$this->assertNotEmpty(MobileDetect::getMobileDetectionRules());
		$this->assertNotEmpty($detect->getMobileDetectionRulesExtended());
		$this->assertNotEmpty($detect->getMobileHeaders());
		$this->assertArrayHasKey('iPhone', MobileDetect::getProperties());
		$detect->setDetectionType(MobileDetect::DETECTION_TYPE_EXTENDED);
		$this->assertArrayHasKey('iPhone', $detect->getRules());
		$detect->setDetectionType(MobileDetect::DETECTION_TYPE_MOBILE);
		$this->assertArrayHasKey('iPhone', $detect->getRules());
		$detect->setDetectionType('invalid');
		$this->assertArrayHasKey('iPhone', $detect->getRules());
		$this->assertSame('17_0', $detect->version('iPhone'));
		$this->assertEqualsWithDelta(17.0, $detect->version('iPhone', MobileDetect::VERSION_TYPE_FLOAT), 0.001);
		$this->assertFalse($detect->version('DoesNotExist'));
		$this->assertFalse($detect->version(''));
		$this->assertEqualsWithDelta(10.23, $detect->prepareVersionNo('10_2/3'), 0.001);
		$this->assertContains($detect->mobileGrade(), [MobileDetect::MOBILE_GRADE_A, MobileDetect::MOBILE_GRADE_B, MobileDetect::MOBILE_GRADE_C]);
	}
}
