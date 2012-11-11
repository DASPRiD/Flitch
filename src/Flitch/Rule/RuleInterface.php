<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule;

use Flitch\File\File;

/**
 * Rule interface.
 */
interface RuleInterface
{
    /**
     * Check a given file for rule violations.
     *
     * @param  File  $file
     * @return void
     */
    public function check(File $file);
}
