<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\File;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Must end with single blank line rule.
 */
class MustEndWithSingleBlankLine extends AbstractRule
{
    /**
     * check(): defined by Rule interface.
     *
     * @see    Rule::check()
     * @param  File $file
     * @return void
     */
    public function check(File $file)
    {
        $lastToken = $file->top();

        if (
            $lastToken->getType() !== T_WHITESPACE
            || $lastToken->getNewlineCount() !== 1
            || $lastToken->getTrailingLineLength() !== 0
        ) {
            $this->addViolation(
                $file, $lastToken->getLine(), $lastToken->getColumn(),
                'File does not end with a single blank line'
            );
        }
    }
}
