<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\File;

use Flitch\Test\RuleTestCase,
    Flitch\File\Tokenizer,
    Flitch\Rule\File\DisallowShortOpenTags;

class DisallowShortOpenTagsTest extends RuleTestCase
{
    public function testShortOpenTag()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?\n"
        );

        $rule = new DisallowShortOpenTags();
        $rule->check($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 1,
                'column'   => 1,
                'message'  => 'Short open tags are not allowed',
                'source'   => 'Flitch\File\DisallowShortOpenTags'
            ),
        ));
    }

    public function testLongOpenTag()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php\n"
        );

        $rule = new DisallowShortOpenTags();
        $rule->check($file);

        $this->assertRuleViolations($file, array());
    }
}
