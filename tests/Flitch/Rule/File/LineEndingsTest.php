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
    Flitch\Rule\File\LineEndings;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class LineEndingsTest extends RuleTestCase
{
    /**
     * @var File
     */
    protected $file;
    
    public function setUp()
    {
        $this->file = new File(
            'foo.php',
            "<?php\r\n//\r//\n"
        );
    }
    
    public function testOnlyAllowLFByDefault()
    {
        $rule = new LineEndings();
        $rule->check($this->file);
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\n", found "\r\n"',
                'source'   => 'Flitch\File\LineEndings'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\n", found "\r"',
                'source'   => 'Flitch\File\LineEndings'
            ),
        ));
    }
    
    public function testOnlyAllowLF()
    {
        $rule = new LineEndings(array(
            'eol-char' => '\n'
        ));
        $rule->check($this->file);
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\n", found "\r\n"',
                'source'   => 'Flitch\File\LineEndings'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\n", found "\r"',
                'source'   => 'Flitch\File\LineEndings'
            ),
        ));
    }
    
    public function testOnlyAllowCR()
    {
        $rule = new LineEndings();
        $rule->check($this->file, array('eol-char' => '\r'));
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\r", found "\r\n"',
                'source'   => 'Flitch\File\LineEndings'
            ),
            array(
                'line'     => 3,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\r", found "\n"',
                'source'   => 'Flitch\File\LineEndings'
            ),
        ));
    }
    
    public function testOnlyAllowCRLF()
    {
        $rule = new LineEndings();
        $rule->check($this->file, array('eol-char' => '\r\n'));
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 2,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\r\n", found "\r"',
                'source'   => 'Flitch\File\LineEndings'
            ),
            array(
                'line'     => 3,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\r\n", found "\n"',
                'source'   => 'Flitch\File\LineEndings'
            ),
        ));
    }
    
    public function testFallbackToLFWithInvalidEolChar()
    {
        $rule = new LineEndings();
        $rule->check($this->file, array('eol-char' => 'foo'));
        
        $this->assertRuleErrors($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\n", found "\r\n"',
                'source'   => 'Flitch\File\LineEndings'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'severity' => 'error',
                'message'  => 'Line must end with "\n", found "\r"',
                'source'   => 'Flitch\File\LineEndings'
            ),
        ));
    }
}
