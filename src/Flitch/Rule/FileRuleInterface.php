<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule;

use Flitch\File\File;

/**
 * File rule interface.
 *
 * Rules implementing this interface will be visited for ever file.
 */
interface FileRuleInterface
{
    /**
     * Check a given file for rule violations.
     *
     * @param  File $file
     * @return void
     */
    public function visitFile(File $file);
}
