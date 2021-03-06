<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
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

        $namespaceFound = false;
        $namespace      = null;
        $namespaceLevel = null;

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

            // Namespace handling.
            if ($type === T_NAMESPACE) {
                $namespaceFound = true;
            } elseif ($namespaceFound) {
                if (in_array($type, array(T_STRING, T_NS_SEPARATOR))) {
                    $namespace .= $lexeme;
                } elseif ($type === ';') {
                    $namespaceFound = false;
                } elseif ($type === '{') {
                    $namespaceFound = false;
                    $namespaceLevel = $level;
                }
            } elseif ($type === '}' && ($level - 1) === $namespaceLevel) {
                $namespace      = null;
                $namespaceLevel = null;
            } elseif (!$namespaceFound && $namespace !== null) {
                $token->setNamespace($namespace);
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
