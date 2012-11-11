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
 * Report interface.
 */
interface ReportInterface
{
    /**
     * Add a file to the report.
     *
     * @param  File $file
     * @return void
     */
    public function addFile(File $file);
}
