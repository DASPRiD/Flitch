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

/**
 * Error representation.
 * 
 * @category   PHPCV
 * @package    PHPCV_File
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class Error
{
    /**#@+
     * Severities.
     */
    const SEVERITY_IGNORE  = 0;
    const SEVERITY_INFO    = 1;
    const SEVERITY_WARNING = 2;
    const SEVERITY_ERROR   = 3;
    /**#@-*/
    
    /**
     * Line the error occured in.
     * 
     * @var integer
     */
    protected $line;
    
    /**
     * Column the error occured in.
     * 
     * @var integer
     */
    protected $column;
    
    /**
     * Severity of the error.
     * 
     * @var integer
     */
    protected $severity;
    
    /**
     * Descriptive message.
     * 
     * @var string
     */
    protected $message;
    
    /**
     * Source rule of the error.
     * 
     * @var string
     */
    protected $source;
    
    /**
     * Create a new error.
     * 
     * @param  integer $line
     * @param  integer $column
     * @param  integer $severity
     * @param  string  $message
     * @param  Rule    $source 
     * @return void
     */
    public function __construct($line, $column, $severity, $message, Rule $source)
    {
        $source = get_class($source);
        
        if (strpos($ruleName, 'PHPCV\\Rule\\') === 0) {
            $source = 'PHPCV\\' . substr($ruleName, strlen('PHPCV\\Rule\\'));
        }
        
        $this->line     = (int) $line;
        $this->column   = (int) $column;
        $this->severity = min(3, max(0, (int) $severity));
        $this->message  = $message;
        $this->source   = $source;
    }
    
    /**
     * Get the line the error occured in.
     * 
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }
    
    /**
     * Get the column the error occured in.
     * 
     * @return integer
     */
    public function getColumn()
    {
        return $this->column;
    }
    
    /**
     * Get the severity of the error.
     * 
     * @return integer
     */
    public function getSeverity()
    {
        return $this->severity;
    }
    
    /**
     * Get the severity as string.
     * 
     * @return string
     */
    public function getSeverityName()
    {
        switch ($this->severity) {
            case self::SEVERITY_IGNORE:
                return 'ignore';
                
            case self::SEVERITY_INFO:
                return 'info';
                
            case self::SEVERITY_WARNING:
                return 'warning';
                
            case self::SEVERITY_ERROR:
                return 'error';
        }
    }
    
    /**
     * Get the descriptive message.
     * 
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Get source rule of the error.
     * 
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }
}
