<?php
/**
 * PHPCV
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   PHPCV
 * @package    PHPCV_Rule
 * @subpackage Whitespace
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\Rule\Whitespace;

use PHPCV\Rule\Rule,
    PHPCV\File\File;

/**
 * No whitespace at end of line rule.
 * 
 * @category   PHPCV
 * @package    PHPCV_Rule
 * @subpackage Whitespace
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class NoWhitespaceAtEndOfLine implements Rule
{
    /**
     * check(): defined by Rule interface.
     * 
     * @see    Rule::check()
     * @param  File  $file
     * @param  array $options
     * @return void
     */
    public function check(File $file, array $options = array())
    {
        foreach ($file->getLines() as $lineNo => $line) {
            if (preg_match('([ \t]+(\r?\n|\r)$)', $line)) {
                $file->addError(new Error(
                    $lineNo,
                    0,
                    Error::SEVERITY_ERROR,
                    'Line contains trailing whitespace',
                    $this
                ));
            }
        }
    }
}
