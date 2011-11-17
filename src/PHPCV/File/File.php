<?php
/**
 * PHPCV
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   PHPCV
 * @package    PHPCV_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace PHPCV\File;

use \ArrayAccess;

/**
 * File representation.
 * 
 * @category   PHPCV
 * @package    PHPCV_File
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
    protected $lines;
    
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
        
        $this->lines = preg_split('(\r?\n|\r)', $source);
        
        // Change the lines array to start counting at 1
        array_unshift($this->lines, '');
        unset($this->lines[0]);
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
