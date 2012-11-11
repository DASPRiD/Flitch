<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\File;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * Encoding rule.
 */
class Encoding extends AbstractRule
{
    /**
     * Possible byte order marks.
     *
     * @var array
     */
    protected static $byteOrderMarks = array(
        'utf-8'    => "\0xef\0xbb\0xbf",
        'utf-16be' => "\0xfe\0xff",
        'utf-16le' => "\0xff\0xfe",
        'utf-32be' => "\0x00\0x00\0xfe\0xff",
        'utf-32le' => "\0xff\0xfe\0x00\0x00",
        'utf-7'    => "\0x2b\0x2f\0x76",
        'utf-1'    => "\0xf7\0x64\0x4c",
        'ebcdic'   => "\0xdd\0x73\0x66\x73",
        'scsu'     => "\0x0e\0xfe\0xff",
        'bocu-1'   => "\0xfb\0xee\0x28",
        'gb18030'  => "\0x84\0x31\0x95\0x33",
    );

    /**
     * Encoding of the source.
     *
     * @var string
     */
    protected $encoding = 'utf-8';

    /**
     * Whether to allow byte order marks.
     *
     * @var boolean
     */
    protected $allowBom = false;

    /**
     * Set encoding.
     *
     * @param  string $encoding
     * @return Encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = strtolower($encoding);
        return $this;
    }

    /**
     * Set whether to allow byte order marks.
     *
     * @param  boolean $allowBom
     * @return Encoding
     */
    public function setAllowBom($allowBom)
    {
        $this->allowBom = (bool) $allowBom;
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
        $source = $file->getSource();

        if (@iconv($this->encoding, $this->encoding, $source) !== $source) {
            $this->addViolation(
                $file, 0, 0,
                sprintf('File is not encoded in "%s"', $this->encoding)
            );

            return;
        }

        if (!$this->allowBom && isset(self::$byteOrderMarks[$this->encoding])) {
            $bom = self::$byteOrderMarks[$this->encoding];

            if (substr($source, 0, strlen($bom)) === $bom) {
                $this->addViolation(
                    $file, 0, 0,
                    sprintf('File starts with a BOM', $this->encoding)
                );
            }
        }
    }
}
