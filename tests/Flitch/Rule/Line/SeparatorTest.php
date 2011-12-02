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
    Flitch\Rule\Line\Separator;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class SeparatorTest extends RuleTestCase
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
    
    public function testOnlyAllowUnixByDefault()
    {
        $rule = new Separator();
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line must end with "\n", found "\r\n"',
                'source'   => 'Flitch\Line\Separator'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'message'  => 'Line must end with "\n", found "\r"',
                'source'   => 'Flitch\Line\Separator'
            ),
        ));
    }
    
    public function testOnlyAllowUnix()
    {
        $rule = new Separator();
        $rule->setEolStyle('unix');
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line must end with "\n", found "\r\n"',
                'source'   => 'Flitch\Line\Separator'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'message'  => 'Line must end with "\n", found "\r"',
                'source'   => 'Flitch\Line\Separator'
            ),
        ));
    }
    
    public function testOnlyAllowMac()
    {
        $rule = new Separator();
        $rule->setEolStyle('mac');
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line must end with "\r", found "\r\n"',
                'source'   => 'Flitch\Line\Separator'
            ),
            array(
                'line'     => 3,
                'column'   => 0,
                'message'  => 'Line must end with "\r", found "\n"',
                'source'   => 'Flitch\Line\Separator'
            ),
        ));
    }
    
    public function testOnlyAllowWindows()
    {
        $rule = new Separator();
        $rule->setEolStyle('windows');
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 2,
                'column'   => 0,
                'message'  => 'Line must end with "\r\n", found "\r"',
                'source'   => 'Flitch\Line\Separator'
            ),
            array(
                'line'     => 3,
                'column'   => 0,
                'message'  => 'Line must end with "\r\n", found "\n"',
                'source'   => 'Flitch\Line\Separator'
            ),
        ));
    }
}
