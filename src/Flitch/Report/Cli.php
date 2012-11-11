<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Report;

use Flitch\File\File;

/**
 * CLI report.
 */
class Cli implements ReportInterface
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
