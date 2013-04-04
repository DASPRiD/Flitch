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
use Flitch\File\Violation;
use Flitch\Rule\AbstractRule;

/**
 * Max line length rule.
 */
class MaxLength extends AbstractRule
{
    /**
     * Limit for emitting errors.
     *
     * @var integer
     */
    protected $errorLimit = 80;

    /**
     * Limit for emitting warnings.
     *
     * @var integer
     */
    protected $warningLimit = null;

    /**
     * Limit for emitting infos.
     *
     * @var integer
     */
    protected $infoLimit = null;

    /**
     * Defines how many spaces a tab takes up.
     *
     * @var integer
     */
    protected $tabExpand = 4;

    /**
     * Set error line length limit.
     *
     * @param  integer|null $length
     * @return MaxLength
     */
    public function setErrorLimit($length)
    {
        $this->errorLimit = ((int) $length) ?: null;
        return $this;
    }

    /**
     * Set warning line length limit.
     *
     * @param  integer|null $length
     * @return MaxLength
     */
    public function setWarningLimit($length)
    {
        $this->warningLimit = ((int) $length) ?: null;
        return $this;
    }

    /**
     * Set info line length limit.
     *
     * @param  integer|null $length
     * @return MaxLength
     */
    public function setInfoLimit($length)
    {
        $this->infoLimit = ((int) $length) ?: null;
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

            $violationLimit = null;
            $severity       = null;

            if ($this->errorLimit !== null && $lineLength > $this->errorLimit) {
                $violationLimit = $this->errorLimit;
                $severity       = Violation::SEVERITY_ERROR;
            } elseif ($this->warningLimit !== null && $lineLength > $this->warningLimit) {
                $violationLimit = $this->warningLimit;
                $severity       = Violation::SEVERITY_WARNING;
            } elseif ($this->infoLimit !== null && $lineLength > $this->infoLimit) {
                $violationLimit = $this->infoLimit;
                $severity       = Violation::SEVERITY_INFO;
            }

            if ($violationLimit !== null) {
                $this->addViolation(
                    $file, $line, 0,
                    sprintf('Line is longer than %d characters', $violationLimit),
                    $severity
                );
            }
        }
    }
}
