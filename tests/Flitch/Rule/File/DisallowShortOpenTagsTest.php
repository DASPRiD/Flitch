<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace FlitchTest\Rule\File;

use Flitch\File\Tokenizer;
use Flitch\Rule\File\DisallowShortOpenTags;
use Flitch\Test\RuleTestCase;

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
