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

namespace Flitch\Rule\File;

use Flitch\Rule\Rule,
    Flitch\File\File;

/**
 * Max line length rule.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MaxLineLength implements Rule
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
        $softLimit = (isset($options['soft-limit']) ? (int) $options['soft-limit'] : 80);
        $hardLimit = (isset($options['hard-limit']) ? (int) $options['hard-limit'] : 120);
        
        foreach ($file->getLines() as $lineNo => $line) {
            if (iconv_strlen($line, 'utf-8') > $hardLimit) {
                $file->addError(new Error(
                    $lineNo, 0, Error::SEVERITY_ERROR,
                    sprintf('Line may not be longer than %d characters', $hardLimit),
                    $this
                ));
            } elseif (iconv_strlen($line, 'utf-8') > $softLimit) {
                $file->addError(new Error(
                    $lineNo, 0, Error::SEVERITY_WARNING,
                    sprintf('Line should not be longer than %d characters', $softLimit),
                    $this
                ));
            }
        }
    }
}
