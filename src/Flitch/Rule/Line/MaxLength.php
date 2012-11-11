<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Line;

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Max line length rule.
 */
class MaxLength extends AbstractRule
{
    /**
     * Max line length limit.
     *
     * @var integer
     */
    protected $limit = 80;

    /**
     * Defines how many spaces a tab takes up.
     *
     * @var integer
     */
    protected $tabExpand = 4;

    /**
     * Set max line length limit.
     *
     * @param  integer $length
     * @return MaxLength
     */
    public function setLimit($length)
    {
        $this->limit = (int) $length;
        return $this;
    }

    /**
     * Set how many spaces a tab takes up.
     *
     * @param  integer $size
     * @return MaxLength
     */
    public function setTabExpand($size)
    {
        $this->tabExpand = (int) $size;
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
            $lineLength = iconv_strlen(
                str_replace("\t", str_repeat(' ', $this->tabExpand),
                $data['content']),
                $file->getEncoding()
            );

            if ($lineLength > $this->limit) {
                $this->addViolation(
                    $file, $line, 0,
                    sprintf('Line may not be longer than %d characters', $this->limit)
                );
            }
        }
    }
}
