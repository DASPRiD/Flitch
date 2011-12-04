<?php
/**
 * Flitch
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to mail@dasprids.de so I can send you a copy immediately.
 *
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace FlitchTest\Rule\Php;

use Flitch\Test\RuleTestCase,
    Flitch\File\Tokenizer,
    Flitch\Rule\Php\DisallowShortOpenTags;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
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
                'source'   => 'Flitch\Php\DisallowShortOpenTags'
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
