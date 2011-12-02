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

namespace FlitchTest\Rule\Line;

use Flitch\Test\RuleTestCase,
    Flitch\File\File,
    Flitch\Rule\Line\MaxLength;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MaxLengthTest extends RuleTestCase
{
    /**
     * @var File
     */
    protected $file;
    
    public function setUp()
    {
        $this->file = new File(
            'foo.php',
            "<?php" . str_repeat(' ', 120)
        );
    }
    
    public function testDefaultLimits()
    {
        $rule = new MaxLength();
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line may not be longer than 80 characters',
                'source'   => 'Flitch\Line\MaxLength'
            ),
        ));
    }
    
    public function testCustomLimits()
    {
        $rule = new MaxLength();
        $rule->setLimit(120);
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line may not be longer than 120 characters',
                'source'   => 'Flitch\Line\MaxLength'
            ),
        ));
    }
    
    public function testHandlingOfTabs()
    {
        $file = new File(
            'foo.php',
            "<?php\n//" . str_repeat('\t', 75)
        );
        
        $rule = new MaxLength();
        $rule->check($file);
        
        $this->assertRuleViolations($file, array(
            array(
                'line'     => 2,
                'column'   => 0,
                'message'  => 'Line may not be longer than 80 characters',
                'source'   => 'Flitch\Line\MaxLength'
            ),
        ));
    }
    
    public function testHandlingOfMultibyteCharacters()
    {
        $file = new File(
            'foo.php',
            "<?php\n//" . str_repeat('ü', 75)
        );
        
        $rule = new MaxLength();
        $rule->check($file);
        
        $this->assertRuleViolations($file, array());
    }
}
