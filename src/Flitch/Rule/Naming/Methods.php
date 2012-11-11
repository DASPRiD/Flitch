<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Naming;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Method name rule.
 */
class Methods extends AbstractRule
{
    /**
     * Magic methods to ignore.
     *
     * @var array
     */
    protected static $magicMethods = array(
        '__construct',
        '__destruct',
        '__call',
        '__callStatic',
        '__get',
        '__set',
        '__isset',
        '__unset',
        '__sleep',
        '__wakeup',
        '__toString',
        '__invoke',
        '__set_state',
        '__clone',
    );

    /**
     * Method name format.
     *
     * @var type
     */
    protected $format = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';

    /**
     * Set format.
     *
     * @param  string $format
     * @return Methods
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

        while ($file->seekTokenType(T_FUNCTION)) {
            if (!$file->seekTokenType(T_STRING, false, '(')) {
                $file->next();
                continue;
            }

            $token = $file->current();

            if (
                !in_array($token->getLexeme(), self::$magicMethods)
                && !preg_match('(^' . $this->format . '$)', $token->getLexeme())
            ) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    sprintf('Method name does not match format "%s"', $this->format)
                );
            }

            $file->next();
        }
    }
}
