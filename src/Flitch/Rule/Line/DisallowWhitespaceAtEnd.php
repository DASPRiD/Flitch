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
 * @subpackage Line
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule\Line;

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Disallow whitespace at end of line rule.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage Line
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class DisallowWhitespaceAtEnd extends AbstractRule
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
        foreach ($file->getLines() as $line => $data) {
            if (preg_match('(\s+$)', $data['content'])) {
                $this->addViolation(
                    $file, $line, 0,
                    'Line may not contain whitespace at end of line'
                );
            }
        }
    }
}
