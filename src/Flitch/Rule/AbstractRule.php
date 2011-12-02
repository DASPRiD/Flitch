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
 * @package    Flitch_Rule
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule;

use Flitch\File\File,
    Flitch\File\Violation;

/**
 * Abstract rule.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
abstract class AbstractRule implements Rule
{
    /**
     * Severity to use for errors.
     * 
     * @var integer
     */
    protected $severity = Violation::SEVERITY_ERROR;
    
    /**
     * Set severity for violations of this rule.
     * 
     * @param  string $severity
     * @return AbstractRule
     */
    public function setSeverity($severity)
    {
        if (null !== ($severity = Violation::getSeverityFromString($options['severity']))) {
            $this->severity = $severity;
        }

        return $this;
    }
    
    /**
     * Add a violation to the current file.
     * 
     * @return void
     */
    protected function addViolation(File $file, $line, $column, $message)
    {
        $source = get_class($this);
        
        if (strpos($source, 'Flitch\\Rule\\') === 0) {
            $source = 'Flitch\\' . substr($source, strlen('Flitch\\Rule\\'));
        }
        
        $file->addViolation(new Violation($line, $column, $this->severity, $message, $source));
    }
}
