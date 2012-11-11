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
use Flitch\Rule\Naming\Constants;
use Flitch\Test\RuleTestCase;

class ConstantsTest extends RuleTestCase
{
    public function testConstantNaming()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php class foo { const BAR = 'baz'; const BAZ = 'bar'; }"
        );

        $rule = new Constants();
        $rule->setFormat('BAZ');
        $rule->check($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 1,
                'column'   => 25,
                'message'  => 'Constant name does not match format "BAZ"',
                'source'   => 'Flitch\Naming\Constants'
            ),
        ));
    }
}
