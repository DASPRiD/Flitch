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

use \ArrayAccess;

/**
 * File representation.
 * 
 * @category   Flitch
 * @package    Flitch_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class File implements ArrayAccess
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
     * Lines of the file.
     * 
     * @var array
     */
    protected $lines = array();
    
    /**
     * Tokens.
     * 
     * @var array
     */
    protected $tokens = array();
    
    /**
     * Errors in this file.
     * 
     * @var array
     */
    protected $errors = array();
    
    /**
     * Create a new file representation.
     * 
     * @param  string $filename
     * @param  string $source
     * @return void
     */
    public function __construct($filename, $source)
    {
        $this->filename = $filename;
        $this->source   = $source;
        
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
     * Add an error to the file.
     * 
     * @param  Error $error
     * @return void
     */
    public function addError(Error $error)
    {
        $this->errors[] = $error;
    }
    
    /**
     * Get all produced errors.
     * 
     * Errors will be sorted by line/column before being returned.
     * 
     * @return array
     */
    public function getErrors()
    {
        usort($this->errors, function(Error $a, Error $b) {
            if ($a->getLine() === $b->getLine()) {
                if ($a->getColumn() === $b->getColumn()) {
                    return 0;
                }
                
                return ($a->getColumn() < $b->getColumn() ? -1 : 1);
            }
            
            return ($a->getLine() < $b->getLine() ? -1 : 1);
        });
        
        return $this->errors;
    }

    /**
     * offsetExists(): defined by ArrayAccess interface.
     * 
     * @see    ArrayAccess::offsetExists()
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->tokens[$offset]);
    }
    
    /**
     * offsetGet(): defined by ArrayAccess interface.
     * 
     * @see    ArrayAccess::offsetGet()
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (isset($this->tokens[$offset])) {
            return $this->tokens[$offset];
        }
        
        return null;
    }
    
    /**
     * offsetSet(): defined by ArrayAccess interface.
     * 
     * @see    ArrayAccess::offsetSet()
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->tokens[$offset] = $value;
    }
    
    /**
     * offsetUnset(): defined by ArrayAccess interface.
     * 
     * @see    ArrayAccess::offsetUnset()
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetUnset($offset)
    {
        if (isset($this->tokens[$offset])) {
            unset($this->tokens[$offset]);
        }
    }
}
