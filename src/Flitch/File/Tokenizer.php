<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\File;

/**
 * File tokenizer.
 */
class Tokenizer
{
    /**
     * Tokenize source code.
     *
     * @param  string $filename
     * @param  string $source
     * @return File
     */
    public function tokenize($filename, $source)
    {
        $file   = new File($filename, $source);
        $line   = 1;
        $column = 1;
        $level  = 0;

        foreach (token_get_all($source) as $token) {
            if (is_array($token)) {
                $type   = $token[0];
                $lexeme = $token[1];
            } else {
                $type   = $token;
                $lexeme = $token;
            }

            $token = new Token($type, $lexeme, $line, $column);

            if ($token->hasNewline()) {
                $line   += $token->getNewLineCount();
                $column  = 1 + $token->getTrailingLineLength();
            } else {
                $column += $token->getLength();
            }

            // Block level increment.
            if (in_array($type, array('(', '{', T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES))) {
                $level++;
            } elseif (in_array($type, array(')', '}'))) {
                $level--;
            }

            $token->setLevel($level);

            $file[] = $token;
        }

        return $file;
    }
}
