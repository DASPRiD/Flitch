<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Line;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;
use Flitch\Rule\FileRuleInterface;

/**
 * Disallow trailing whitespace rule.
 */
class DisallowTrailingWhitespace extends AbstractRule implements FileRuleInterface
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
        foreach ($file->getLines() as $line => $data) {
            if (preg_match('(\s+$)', $data['content'])) {
                $this->addViolation(
                    $file, $line, 0,
                    'Line contains trailing whitespace'
                );
            }
        }
    }
}
