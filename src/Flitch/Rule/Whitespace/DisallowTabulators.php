<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Whitespace;

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Disallow tabulators rule.
 */
class DisallowTabulators extends AbstractRule
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

        while ($file->seekTokenType(T_WHITESPACE)) {
            $token = $file->current();

            if (false !== strpos($token->getLexeme(), "\t")) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    'Tabulators are not allowed'
                );
            }

            $file->next();
        }
    }
}
