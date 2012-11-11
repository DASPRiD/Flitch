<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\File;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Must start with open tag rule.
 */
class MustStartWithOpenTag extends AbstractRule
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
        if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
            $this->addViolation(
                $file, 1, 1,
                'File must start with an open tag'
            );
        }
    }
}
