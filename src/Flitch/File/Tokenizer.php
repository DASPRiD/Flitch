<?php
/**
 * Flitch
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Flitch
 * @package    Flitch_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\File;

/**
 * File tokenizer.
 * 
 * @category   Flitch
 * @package    Flitch_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
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
            
            $file[] = $token;
        }
        
        return $file;
    }
}
