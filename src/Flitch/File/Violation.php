<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\File;

/**
 * Violation representation.
 */
class Violation
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
     * Create a new violation.
     *
     * @param  integer $line
     * @param  integer $column
     * @param  integer $severity
     * @param  string  $message
     * @param  string  $source
     * @return void
     */
    public function __construct($line, $column, $severity, $message, $source)
    {
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

    /**
     * Get severity from a string.
     *
     * @param  string $severity
     * @return integer
     */
    public static function getSeverityFromString($severity)
    {
        switch ($severity) {
            case 'ignore':
                return self::SEVERITY_IGNORE;

            case 'info':
                return self::SEVERITY_INFO;

            case 'warning':
                return self::SEVERITY_WARNING;

            case 'error':
                return self::SEVERITY_ERROR;
        }

        return null;
    }
}
