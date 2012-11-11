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
 * @package    Flitch_Rule
 * @subpackage File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule\Naming;

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Constant name rule.
 *
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage Naming
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
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
