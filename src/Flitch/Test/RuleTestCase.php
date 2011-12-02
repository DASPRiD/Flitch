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
     * Assert a specific set of errors.
     * 
     * @param  File  $file
     * @param  array $expectedErrors
     * @return void
     */
    public function assertRuleErrors(File $file, array $expectedErrors)
    {
        // Get all errors and convert them to an array for comparision.
        $errors = array();
        
        foreach ($file->getErrors() as $error) {
            $errors[] = array(
                'line'     => $error->getLine(),
                'column'   => $error->getColumn(),
                'severity' => $error->getSeverityName(),
                'message'  => $error->getMessage(),
                'source'   => $error->getSource()
            );
        }
        
        $this->assertEquals($expectedErrors, $errors);
    }
}
