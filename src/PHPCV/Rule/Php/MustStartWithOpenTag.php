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
 * @subpackage Php
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\Rule\Php;

use PHPCV\Rule\Rule,
    PHPCV\File\File;

/**
 * Must start with open tag rule.
 * 
 * @category   PHPCV
 * @package    PHPCV_Rule
 * @subpackage Php
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MustStartWithOpenTag implements Rule
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
        if ($file[0]->getType() !== T_OPEN_TAG) {
            $file->addError(1, 'File must start with an open tag');
        }
    }
}
