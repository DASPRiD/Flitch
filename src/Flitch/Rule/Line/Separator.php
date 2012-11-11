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

/**
 * Line separator rule.
 */
class Separator extends AbstractRule
{
    /**
     * Allowed line-ending.
     *
     * @var string
     */
    protected $eolChar = "\n";

    /**
     * Name of line-ending character.
     *
     * @var string
     */
    protected $eolName = '\n';

    /**
     * Set end-of-line style.
     *
     * Valid values are windows, unix and mac.
     *
     * @param  string $style
     * @return Separator
     */
    public function setEolStyle($style)
    {
        $style = strtolower($style);

        switch ($style) {
            case 'windows':
                $this->eolChar = "\r\n";
                $this->eolName = '\r\n';
                break;

            case 'unix':
                $this->eolChar = "\n";
                $this->eolName = '\n';
                break;

            case 'mac':
                $this->eolChar = "\r";
                $this->eolName = '\r';
                break;
        }

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
            if ($data['ending'] !== '' && $data['ending'] !== $this->eolChar) {
                $ending = str_replace(array("\r", "\n"), array('\r', '\n'), $data['ending']);

                $this->addViolation(
                    $file, $line, 0,
                    sprintf('Line must end with "%s", found "%s"', $this->eolName, $ending)
                );
            }
        }
    }
}
