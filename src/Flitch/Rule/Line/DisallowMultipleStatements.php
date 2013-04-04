<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Line;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Disallow multiple statements rule.
 */
class DisallowMultipleStatements extends AbstractRule
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

        $previousToken = null;

        while ($file->seekTokenType(';')) {
            $currentToken = $file->current();
            $file->next();

            $secondStatement = false;

            while ($file->valid()) {
                $token     = $file->current();
                $tokenType = $token->getType();

                if (in_array($tokenType, array(T_COMMENT, T_DOC_COMMENT))) {
                    $lexeme = $token->getLexeme();

                    if (strpos($lexeme, '//') === 0 || strpos($lexeme, '#') === 0) {
                        // Single line comments end the line
                        break;
                    } elseif ($token->getNewlineCount() > 0) {
                        // So do block comments with new lines
                        break;
                    }
                } elseif ($tokenType === T_WHITESPACE) {
                    if ($token->getNewlineCount() > 0) {
                        // Whitespace new lines are fine as well
                        break;
                    }
                } else {
                    $secondStatement = true;
                    break;
                }

                $file->next();
            }

            if ($previousToken !== null) {


                if ($currentToken->getLine() === $previousToken->getLine()) {
                    $this->addViolation(
                        $file, $currentToken->getLine(), $currentToken->getColumn(),
                        'Found multiple statements on same line'
                    );
                }
            }

            $previousToken = $currentToken;

            $file->next();
        }
    }
}
