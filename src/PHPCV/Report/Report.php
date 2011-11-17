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
 * @package    PHPCV_Report
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\Report;

use PHPCV\File\File;

/**
 * Report interface.
 * 
 * @category   PHPCV
 * @package    PHPCV_Checkstyle
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
interface Report
{
    /**
     * Add a file to the report.
     * 
     * @param  File $file
     * @return void
     */
    public function addFile(File $file);
}
