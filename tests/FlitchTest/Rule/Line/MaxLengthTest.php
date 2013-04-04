<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\Line;

use Flitch\File\File;
use Flitch\Rule\Line\MaxLength;
use Flitch\Test\RuleTestCase;

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
        $rule->visitFile($this->file);

        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line is longer than 80 characters',
                'source'   => 'Flitch\Line\MaxLength'
            ),
        ));
    }

    public function testCustomLimits()
    {
        $rule = new MaxLength();
        $rule->setErrorLimit(120);
        $rule->visitFile($this->file);

        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line is longer than 120 characters',
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
        $rule->visitFile($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 2,
                'column'   => 0,
                'message'  => 'Line is longer than 80 characters',
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
        $rule->visitFile($file);

        $this->assertRuleViolations($file, array());
    }
}
