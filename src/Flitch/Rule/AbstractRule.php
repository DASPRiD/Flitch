<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule;

use Flitch\File\File;
use Flitch\File\Violation;

/**
 * Abstract rule.
 */
abstract class AbstractRule implements RuleInterface
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
     * @param  File    $file
     * @param  integer $line
     * @param  integer $column
     * @param  string  $message
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
