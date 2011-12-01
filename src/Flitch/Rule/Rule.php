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
 * @package    Flitch_Rule
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule;

use Flitch\File\File;

/**
 * Rule interface.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
interface Rule
{
    /**
     * Check a given file for rule violations.
     * 
     * @param  File  $file
     * @param  array $options
     * @return void
     */
    public function check(File $file, array $options = array());
}
