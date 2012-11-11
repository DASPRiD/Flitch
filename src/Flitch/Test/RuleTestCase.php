<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Test;

use PHPUnit_Framework_TestCase as TestCase,
    Flitch\File\File;

/**
 * Test case class for testing rules.
 */
class RuleTestCase extends TestCase
{
    /**
     * Assert a specific set of violations.
     *
     * @param  File  $file
     * @param  array $expectedViolations
     * @return void
     */
    public function assertRuleViolations(File $file, array $expectedViolations)
    {
        // Get all violations and convert them to an array for comparision.
        $violations = array();

        foreach ($file->getViolations() as $error) {
            $violations[] = array(
                'line'     => $error->getLine(),
                'column'   => $error->getColumn(),
                'message'  => $error->getMessage(),
                'source'   => $error->getSource()
            );
        }

        $this->assertEquals($expectedViolations, $violations);
    }
}
