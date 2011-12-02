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
 * @package    Flitch_Report
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Report;

use Flitch\File\File;

/**
 * CLI report.
 * 
 * @category   Flitch
 * @package    Flitch_Report
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class Cli implements Report
{
    /**
     * Whether the first line was already processed.
     * 
     * @var boolean
     */
    protected $firstFile = true;
    
    /**
     * addFile(): defined by Report interface.
     * 
     * @see    Report::addFile()
     * @param  File $file
     * @return void
     */
    public function addFile(File $file)
    {
        $violations = $file->getViolations();
        
        if ($violations) {
            if ($this->firstFile) {
                $this->firstFile = false;
            } else {
                echo PHP_EOL;
            }
            
            echo $file->getFilename() . ':' . PHP_EOL;
            echo str_repeat('-', 80) . PHP_EOL;
            
            foreach ($violations as $violation) {
                echo $violation->getLine() . ':';
                
                if ($violation->getColumn() > 0) {
                    echo $violation->getColumn() . ':';
                }
                
                echo $violation->getSeverityName() . ': ';
                echo $violation->getMessage() . PHP_EOL;
            }
        }
    }
}
