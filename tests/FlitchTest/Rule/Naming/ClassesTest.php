<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\Naming;

use Flitch\File\Tokenizer;
use Flitch\Rule\Naming\Classes;
use Flitch\Test\RuleTestCase;

class ClassesTest extends RuleTestCase
{
    public function testClassNaming()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php class foo {} class bar {}"
        );

        $file->rewind();
        $file->seekTokenType(T_CLASS);

        $rule = new Classes();
        $rule->setFormat('bar');
        $rule->visitToken($file);

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
