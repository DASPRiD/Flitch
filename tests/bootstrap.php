<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

// Set error reporting pretty high
error_reporting(E_ALL | E_STRICT);

// Get base, application and tests path
define('BASE_PATH',  dirname(__DIR__));
define('TESTS_PATH', __DIR__);

// Define filters for clover report
$filter = new PHP_CodeCoverage_Filter();
$filter->addDirectoryToBlacklist(TESTS_PATH);
$filter->addDirectoryToBlacklist(BASE_PATH . '/bin');
$filter->addFilesToBlacklist(array(
    BASE_PATH . '/src/autoload_classmap.php',
    BASE_PATH . '/src/autoload_function.php',
    BASE_PATH . '/src/autoload_register.php',
));
$filter->addDirectoryToWhitelist(BASE_PATH . '/src', '.php');
unset($filter);

// Load autoloader
require_once BASE_PATH . '/src/autoload_register.php';
