<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Naming;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Constant name rule.
 */
class Constants extends AbstractRule
{
    /**
     * Constant name format.
     *
     * @var type
     */
    protected $format = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';

    /**
     * Set format.
     *
     * @param  string $format
     * @return Constants
     */
    public function setFormat($format)
    {
        $this->format = $format;
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
        $file->rewind();

        while ($file->seekTokenType(T_CONST)) {
            if (!$file->seekTokenType(T_STRING)) {
                $file->next();
                continue;
            }

            $token = $file->current();

            if (!preg_match('(^' . $this->format . '$)', $token->getLexeme())) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    sprintf('Constant name does not match format "%s"', $this->format)
                );
            }

            $file->next();
        }
    }
}
