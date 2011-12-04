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
 * @subpackage Whitespace
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule\Whitespace;

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Disallow tabulators rule.
 *
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage Whitespace
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class DisallowTabulators extends AbstractRule
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

        while ($file->seekTokenType(T_WHITESPACE)) {
            $token = $file->current();

            if (false !== strpos($token->getLexeme(), "\t")) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    'Tabulators are not allowed'
                );
            }

            $file->next();
        }
    }
}
