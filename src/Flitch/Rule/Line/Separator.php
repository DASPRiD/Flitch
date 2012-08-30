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
 * Line separator rule.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage Line
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class Separator extends AbstractRule
{
    /**
     * Allowed line-ending.
     * 
     * @var string
     */
    protected $eolChar = "\n";
    
    /**
     * Name of line-ending character.
     * 
     * @var string
     */
    protected $eolName = '\n';
    
    /**
     * Set end-of-line style.
     * 
     * Valid values are windows, unix and mac.
     * 
     * @param  string $style
     * @return Separator
     */
    public function setEolStyle($style)
    {
        $style = strtolower($style);
        
        switch ($style) {
            case 'windows':
                $this->eolChar = "\r\n";
                $this->eolName = '\r\n';
                break;
            
            case 'unix':
                $this->eolChar = "\n";
                $this->eolName = '\n';
                break;
            
            case 'mac':
                $this->eolChar = "\r";
                $this->eolName = '\r';
                break;
        }
        
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
        foreach ($file->getLines() as $line => $data) {
            if ($data['ending'] !== '' && $data['ending'] !== $this->eolChar) {
                $ending = str_replace(array("\r", "\n"), array('\r', '\n'), $data['ending']);

                $this->addViolation(
                    $file, $line, 0,
                    sprintf('Line must end with "%s", found "%s"', $this->eolName, $ending)
                );
            }
        }
    }
}
