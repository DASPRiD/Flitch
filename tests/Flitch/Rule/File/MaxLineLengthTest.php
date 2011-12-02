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
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace FlitchTest\Rule\File;

use Flitch\Test\RuleTestCase,
    Flitch\File\File,
    Flitch\Rule\File\MaxLineLength;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MaxLineLengthTest extends RuleTestCase
{
    /**
     * @var File
     */
    protected $file;
    
    public function setUp()
    {
        $this->file = new File(
            'foo.php',
            "<?php" . str_repeat(' ', 120) . "\n" . str_repeat(' ', 81)
        );
    }
    
    public function testDefaultLimits()
    {
        $rule = new MaxLineLength();
        $rule->check($this->file);
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line may not be longer than 120 characters',
                'source'   => 'Flitch\File\MaxLineLength'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'severity' => 'warning',
                'message'  => 'Line should not be longer than 80 characters',
                'source'   => 'Flitch\File\MaxLineLength'
            ),
        ));
    }
    
    public function testCustomLimits()
    {
        $rule = new MaxLineLength();
        $rule->check($this->file, array(
            'hard-limit' => 80,
            'soft-limit' => 40
        ));
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line may not be longer than 80 characters',
                'source'   => 'Flitch\File\MaxLineLength'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line may not be longer than 80 characters',
                'source'   => 'Flitch\File\MaxLineLength'
            ),
        ));
    }
    
    public function testProperHandlingWithUtf8Characters()
    {
        $file = new File(
            'foo.php',
            "<?php\n//" . str_repeat('ü', 75)
        );
        
        $rule = new MaxLineLength();
        $rule->check($file);
        
        $this->assertRuleErrors($file, array());
    }
}
