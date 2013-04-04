<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Whitespace;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;
use Flitch\Rule\TokenRuleInterface;

/**
 * Disallow tabulators rule.
 */
class DisallowTabulators extends AbstractRule implements TokenRuleInterface
{
    /**
     * getListenerTokens(): defined by TokenRuleInterface.
     *
     * @see    TokenRuleInterface::getListenerTokens()
     * @return array
     */
    public function getListenerTokens()
    {
        return array(
            T_WHITESPACE,
        );
    }

    /**
     * visitToken(): defined by TokenRuleInterface.
     *
     * @see    TokenRuleInterface::visitToken()
     * @param  File $file
     * @return void
     */
    public function visitToken(File $file)
    {
        $token = $file->current();

        if (false !== strpos($token->getLexeme(), "\t")) {
            $this->addViolation(
                $file, $token->getLine(), $token->getColumn(),
                'Tabulator found'
            );
        }
    }
}
