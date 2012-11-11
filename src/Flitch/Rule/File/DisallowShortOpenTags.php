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
 * No short open tags rule.
 */
class DisallowShortOpenTags extends AbstractRule
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
        $file->rewind();

        while ($file->seekTokenType(T_OPEN_TAG)) {
            $token = $file->current();

            if (strpos($token->getLexeme(), '<' . '?php') !== 0) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    'Short open tags are not allowed'
                );
            }

            $file->next();
        }
    }
}
