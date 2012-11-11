<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\File;

use Flitch\Exception;
use SeekableIterator;
use SplDoublyLinkedList;

/**
 * File representation.
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
     * the position is not changed. In case a stopper is supplied, the seeking
     * will stop at the given token.
     *
     * @param  mixed   $type
     * @param  boolean $backwards
     * @param  mixed   $stopper
     * @return boolean
     */
    public function seekTokenType($type, $backwards = false, $stopper = null)
    {
        $currentPosition = $this->key();

        while ($this->valid()) {
            $current = $this->current()->getType();

            if (
                $stopper !== null && (is_array($stopper)
                && in_array($current, $stopper)) || $current === $stopper
            ) {
                break;
            }

            if ((is_array($type) && in_array($current, $type)) || $current === $type) {
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
     * Seek to the next line.
     *
     * @return boolean
     */
    public function seekNextLine()
    {
        $line = $this->current()->getLine();

        while (true) {
            $this->next();

            if (!$this->valid()) {
                return false;
            } elseif ($this->current()->getLine() > $line) {
                return true;
            }
        }
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
