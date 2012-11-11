<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\Php;

use Flitch\File\Tokenizer;
use Flitch\Rule\Whitespace\DisallowTabulators;
use Flitch\Test\RuleTestCase;

class DisallowTabulatorsTest extends RuleTestCase
{
    public function testTabulators()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php\n\t"
        );

        $rule = new DisallowTabulators();
        $rule->check($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 2,
                'column'   => 1,
                'message'  => 'Tabulators are not allowed',
                'source'   => 'Flitch\Whitespace\DisallowTabulators'
            ),
        ));
    }
}
