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

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Must start with open tag rule.
 *
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MustStartWithOpenTag extends AbstractRule
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
        if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
            $this->addViolation(
                $file, 1, 1,
                'File must start with an open tag'
            );
        }
    }
}
