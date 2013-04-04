<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\File;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;
use Flitch\Rule\FileRuleInterface;

/**
 * Must start with open tag rule.
 */
class MustStartWithOpenTag extends AbstractRule implements FileRuleInterface
{
    /**
     * visitFile(): defined by FileRuleInterface.
     *
     * @see    FileRuleInterface::visitFile()
     * @param  File $file
     * @return void
     */
    public function visitFile(File $file)
    {
        if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
            $this->addViolation(
                $file, 1, 1,
                'File does not start with PHP open tag'
            );
        }
    }
}
