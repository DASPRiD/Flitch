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
 * Token representation.
 *
 * @category   Flitch
 * @package    Flitch_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class Token
{
    /**
     * Token type.
     *
     * @var mixed
     */
    protected $type;

    /**
     * Token lexeme.
     *
     * @var string
     */
    protected $lexeme;

    /**
     * Token line.
     *
     * @var integer
     */
    protected $line;

    /**
     * Token column
     *
     * @var integer
     */
    protected $column;

    /**
     * Code block level.
     *
     * @var integer
     */
    protected $level;

    /**
     * Create a new token.
     *
     * @param  mixed   $type
     * @param  string  $lexeme
     * @param  integer $line
     * @param  integer $column
     * @return void
     */
    public function __construct($type, $lexeme = null, $line, $column)
    {
        $this->type   = $type;
        $this->lexeme = $lexeme;
        $this->line   = $line;
        $this->column = $column;
    }

    /**
     * Get token type.
     *
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get token lexeme.
     *
     * @return string
     */
    public function getLexeme()
    {
        return $this->lexeme;
    }

    /**
     * Get token line.
     *
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Get token column.
     *
     * @return integer
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Set block level.
     *
     * @param  integer $level
     * @return Token
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get block level.
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Check if the token contains newline characters.
     *
     * @return boolean
     */
    public function hasNewline()
    {
        if (preg_match('([\r\n])', $this->lexeme)) {
            return true;
        }

        return false;
    }

    /**
     * Get number of newlines.
     *
     * @return integer
     */
    public function getNewlineCount()
    {
        preg_match_all('(\n|\r\n?)', $this->lexeme, $matches, PREG_SET_ORDER);

        return count($matches);
    }

    /**
     * Get length of the last line.
     *
     * @return integer
     */
    public function getTrailingLineLength()
    {
        return iconv_strlen(
            substr(strrchr($this->lexeme, "\n") ?: strrchr($this->lexeme, "\r"), 1),
            'utf-8'
        );
    }

    /**
     * Get length of the entire lexeme.
     *
     * @return integer
     */
    public function getLength()
    {
        return iconv_strlen($this->lexeme, 'utf-8');
    }
}
