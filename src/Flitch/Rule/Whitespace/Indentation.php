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
 * Indentation rule.
 */
class Indentation extends AbstractRule
{
    /**
     * Indentation style, can be "space" or "tab".
     *
     * @var string
     */
    protected $indentStyle = 'space';

    /**
     * Number of indentation characters per level.
     *
     * @var integer
     */
    protected $indentCount = 4;

    /**
     * Set indentation style.
     *
     * @param  string $style
     * @return Indentation
     */
    public function setIndentStyle($style)
    {
        $style = strtolower($style);

        switch ($style) {
            case 'space':
            case 'tab':
                $this->indentStyle = $style;
                break;
        }

        return $this;
    }

    /**
     * Set indentation count.
     *
     * @param  integer $count
     * @return Indentation
     */
    public function setIndentCount($count)
    {
        $this->indentCount = max(1, (int) $count);
        return $this;
    }

    /**
     * check(): defined by Rule interface.
     *
     * @see    Rule::check()
     * @param  File  $file
     * @return void
     */
    public function check(File $file)
    {
        $indentation = str_repeat(($this->indentStyle === 'space' ? ' ' : "\t"), $this->indentCount);

        $file->rewind();

        while (true) {
            $token = $file->current();
            $level = $token->getLevel();

            $file->next();
            if ($file->current()->getType() === '}' || $file->current()->getType() === ')') {
                $level--;
            }
            $file->prev();

            $expectedIndentation = str_repeat($indentation, $level);
            $actualIndentation   = $token->getTrailingWhitespace();

            if ($expectedIndentation !== $actualIndentation) {
                $this->addViolation($file, $token, $column, $message);
            }

            if (!$file->seekNextLine()) {
                return;
            }
        }
    }
}
