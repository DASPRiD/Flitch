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
 * @package    Flitch_Test
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Test;

use PHPUnit_Framework_TestCase as TestCase,
    Flitch\File\File;

/**
 * Test case class for testing rules.
 * 
 * @category   Flitch
 * @package    Flitch_Test
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
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
