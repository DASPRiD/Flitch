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
 * Token rule interface.
 *
 * Rules implementing this interface will be able to register for certain tokens
 * and be notified for each occurence.
 */
interface TokenRuleInterface
{
    /**
     * Get all tokens the rule should listen to.
     *
     * @return array
     */
    public function getListenerTokens();

    /**
     * Check a given file for rule violations at the given position.
     *
     * @param  File $file
     * @return void
     */
    public function visitToken(File $file);
}
