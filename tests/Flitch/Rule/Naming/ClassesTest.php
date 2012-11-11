<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\Naming;

use Flitch\Test\RuleTestCase,
    Flitch\File\Tokenizer,
    Flitch\Rule\Naming\Classes;

class ClassesTest extends RuleTestCase
{
    public function testClassNaming()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php class foo {} class bar {}"
        );

        $rule = new Classes();
        $rule->setFormat('bar');
        $rule->check($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 1,
                'column'   => 13,
                'message'  => 'Class name does not match format "bar"',
                'source'   => 'Flitch\Naming\Classes'
            ),
        ));
    }
}
