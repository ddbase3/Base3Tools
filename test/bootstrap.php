<?php declare(strict_types=1);

$frameworkRoot = dirname(__DIR__, 3) . '/';
if (!defined('DIR_ROOT')) define('DIR_ROOT', $frameworkRoot);
if (!defined('DIR_PLUGIN')) define('DIR_PLUGIN', DIR_ROOT . 'plugin/');
if (!defined('DIR_TEST')) define('DIR_TEST', DIR_ROOT . 'test/');
require_once DIR_ROOT . 'bootstrap/Autoloader.php';
\Base3\Bootstrap\Autoloader::register();

$temporaryDirectory = sys_get_temp_dir() . '/base3tools-unit-' . getmypid() . '/';
if (!defined('DIR_LOCAL')) define('DIR_LOCAL', $temporaryDirectory . 'local/');
if (!defined('DIR_USERFILES')) define('DIR_USERFILES', $temporaryDirectory . 'userfiles/');

foreach ([DIR_LOCAL, DIR_USERFILES, DIR_LOCAL . 'Observation/', DIR_LOCAL . 'FileStatus/'] as $directory) {
	if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
		throw new RuntimeException('Cannot create isolated unit-test directory: ' . $directory);
	}
}
