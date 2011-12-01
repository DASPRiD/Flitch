<?php
/**
 * Flitch
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Flitch
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

// Set error reporting pretty high
error_reporting(E_ALL | E_STRICT);

// Get base, application and tests path
define('BASE_PATH',  dirname(__DIR__));
define('TESTS_PATH', __DIR__);

// Define filters for clover report
$filter = PHP_CodeCoverage_Filter::getInstance();
$filter->addDirectoryToBlacklist(TESTS_PATH);
$filter->addDirectoryToBlacklist(BASE_PATH . '/bin');
$filter->addFilesToBlacklist(array(
    BASE_PATH . '/src/autoload_classmap.php',
    BASE_PATH . '/src/autoload_function.php',
    BASE_PATH . '/src/autoload_register.php',
));
$filter->addDirectoryToWhitelist(BASE_PATH . '/src', '.php');

// Load autoloader
require_once BASE_PATH . '/src/autoload_register.php';