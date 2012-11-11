<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\Php;

use Flitch\Test\RuleTestCase,
    Flitch\File\Tokenizer,
    Flitch\Rule\Whitespace\DisallowTabulators;

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
