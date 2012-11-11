<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Line;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Disallow trailing whitespace rule.
 */
class DisallowTrailingWhitespace extends AbstractRule
{
    /**
     * check(): defined by Rule interface.
     *
     * @see    Rule::check()
     * @param  File  $file
     * @return void
     */
    public function check(File $file)
    {
        foreach ($file->getLines() as $line => $data) {
            if (preg_match('(\s+$)', $data['content'])) {
                $this->addViolation(
                    $file, $line, 0,
                    'Line may not contain trailing whitespace'
                );
            }
        }
    }
}
