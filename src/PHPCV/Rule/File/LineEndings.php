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
 * @subpackage File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\Rule\File;

use PHPCV\Rule\Rule,
    PHPCV\File\File;

/**
 * Line endings rule.
 * 
 * @category   PHPCV
 * @package    PHPCV_Rule
 * @subpackage File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class LineEndings implements Rule
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
        $eolChar = "\n";
        $eolName = '\n';
        
        if (isset($options['eol-char'])) {
            switch ($options['eol-char']) {
                case '\r\n':
                    $eolChar = "\r\n";
                    $eolName = '\r\n';
                    break;
                
                case '\n':
                    $eolChar = "\n";
                    $eolName = '\n';
                    break;
                
                case '\r':
                    $eolChar = "\r";
                    $eolName = '\r';
                    break;
            }
        }
        
        $eolChar = (isset($options['eol-char']) ? $options['eol-char'] : '\n');
        
        foreach ($file->getLines() as $lineNo => $line) {
            if (preg_match('((\r?\n|\r)$)', $line, $matches)) {
                if ($matches[1] !== $eolChar) {
                    $file->addError(new Error(
                        $lineNo,
                        0,
                        Error::SEVERITY_ERROR,
                        sprintf('Line must end with "%s"', $eolName),
                        $this
                    ));
                }
            }
        }
    }
}
