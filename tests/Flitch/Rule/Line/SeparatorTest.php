<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\Line;

use Flitch\Test\RuleTestCase,
    Flitch\File\File,
    Flitch\Rule\Line\Separator;

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
