<?php declare(strict_types=1);

$pluginSource = dirname(__DIR__) . '/src/';
$frameworkSource = dirname(__DIR__, 3) . '/src/';
$testSource = __DIR__ . '/';

spl_autoload_register(static function (string $class) use ($pluginSource, $frameworkSource, $testSource): void {
	foreach ([
		'Base3Tools\\Test\\' => $testSource,
		'Base3Tools\\' => $pluginSource,
		'Base3\\' => $frameworkSource
	] as $namespace => $directory) {
		if (!str_starts_with($class, $namespace)) continue;
		$filename = $directory . str_replace('\\', '/', substr($class, strlen($namespace))) . '.php';
		if (is_file($filename)) require_once $filename;
		return;
	}
});

$temporaryDirectory = sys_get_temp_dir() . '/base3tools-unit-' . getmypid() . '/';
if (!defined('DIR_LOCAL')) define('DIR_LOCAL', $temporaryDirectory . 'local/');
if (!defined('DIR_USERFILES')) define('DIR_USERFILES', $temporaryDirectory . 'userfiles/');

foreach ([DIR_LOCAL, DIR_USERFILES, DIR_LOCAL . 'Observation/', DIR_LOCAL . 'FileStatus/'] as $directory) {
	if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
		throw new RuntimeException('Cannot create isolated unit-test directory: ' . $directory);
	}
}
