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
use Flitch\Rule\Naming\Methods;
use Flitch\Test\RuleTestCase;

class MethodsTest extends RuleTestCase
{
    public function testMethodNaming()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php class foo { function foo() {} function bar() {} }"
        );

        $rule = new Methods();
        $rule->setFormat('bar');
        $rule->check($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 1,
                'column'   => 28,
                'message'  => 'Method name does not match format "bar"',
                'source'   => 'Flitch\Naming\Methods'
            ),
        ));
    }

    public function testNoViolationOnMagicMethods()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php class foo { function __construct() {} }"
        );

        $rule = new Methods();
        $rule->setFormat('bar');
        $rule->check($file);

        $this->assertRuleViolations($file, array());
    }

    public function testNoViolationOnClosures()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php function() {}; class foo {}"
        );

        $rule = new Methods();
        $rule->setFormat('bar');
        $rule->check($file);

        $this->assertRuleViolations($file, array());
    }
}
