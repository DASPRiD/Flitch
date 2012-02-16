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
 * @package    Flitch_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\File;

use SplDoublyLinkedList,
    SeekableIterator,
    Flitch\Exception;

/**
 * File representation.
 *
 * @category   Flitch
 * @package    Flitch_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class File extends SplDoublyLinkedList implements SeekableIterator
{
    /**
     * Filename of the represented file.
     *
     * @var string
     */
    protected $filename;

    /**
     * Source code of the represented file.
     *
     * @var string
     */
    protected $source;

    /**
     * Encoding of the file.
     *
     * @var string
     */
    protected $encoding;

    /**
     * Lines of the file.
     *
     * @var array
     */
    protected $lines = array();

    /**
     * Violations in this file.
     *
     * @var array
     */
    protected $violations = array();

    /**
     * Create a new file representation.
     *
     * @param  string $filename
     * @param  string $source
     * @param  string $encoding
     * @return void
     */
    public function __construct($filename, $source, $encoding = 'utf-8')
    {
        $this->filename = $filename;
        $this->source   = $source;
        $this->encoding = $encoding;

        // Split source into line arrays, containing both content and ending.
        preg_match_all('((?<content>.*?)(?<ending>\n|\r\n?|$))', $source, $matches, PREG_SET_ORDER);

        foreach ($matches as $index => $line) {
            $this->lines[$index + 1] = array(
                'content' => $line['content'],
                'ending'  => $line['ending']
            );
        }
    }

    /**
     * Get the filename of the represented file.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Get the source code of the represented file.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get all lines of the source code as array.
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Get encoding of the file.
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Add a violation to the file.
     *
     * @param  Violation $violation
     * @return void
     */
    public function addViolation(Violation $violation)
    {
        $this->violations[] = $violation;
    }

    /**
     * Get all produced violations.
     *
     * Violations will be sorted by line/column before being returned.
     *
     * @return array
     */
    public function getViolations()
    {
        usort($this->violations, function(Violation $a, Violation $b) {
            if ($a->getLine() === $b->getLine()) {
                if ($a->getColumn() === $b->getColumn()) {
                    return 0;
                }

                return ($a->getColumn() < $b->getColumn() ? -1 : 1);
            }

            return ($a->getLine() < $b->getLine() ? -1 : 1);
        });

        return $this->violations;
    }

    /**
     * Seek to a specific token type.
     *
     * Returns true on success and false if the token can not be found. Seeking
     * starts from current element. If the current token matches the given type,
     * the position is not changed.
     *
     * @param  mixed   $type
     * @param  boolean $backwards
     * @return boolean
     */
    public function seekTokenType($type, $backwards = false)
    {
        $currentPosition = $this->key();

        while ($this->valid()) {
            if ($this->current()->getType() === $type) {
                return true;
            }

            if ($backwards) {
                $this->prev();
            } else {
                $this->next();
            }
        }

        $this->seek($currentPosition);

        return false;
    }

    /**
     * seek(): defined by SeekableIterator interface.
     *
     * @see    SeekableIterator::seek()
     * @param  integer $position
     * @return void
     */
    public function seek($position)
    {
        $this->rewind();
        $current = 0;

        while ($current < $position) {
            if (!$this->valid()) {
                throw new Exception\OutOfBoundsException('Invalid seek position');
            }

            $this->next();
            $current++;
        }
    }
}
