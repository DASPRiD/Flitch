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

namespace FlitchTest\Rule\File;

use Flitch\Test\RuleTestCase,
    Flitch\File\Tokenizer,
    Flitch\Rule\File\MustStartWithOpenTag;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MustStartWithOpenTagTest extends RuleTestCase
{
    public function testLeadingWhitespace()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            " <?php\n"
        );

        $rule = new MustStartWithOpenTag();
        $rule->check($file);

        $this->assertRuleViolations($file, array(
            array(
                'line'     => 1,
                'column'   => 1,
                'message'  => 'File must start with an open tag',
                'source'   => 'Flitch\File\MustStartWithOpenTag'
            ),
        ));
    }

    public function testNoLeadingWhitespace()
    {
        $tokenizer = new Tokenizer();
        $file      = $tokenizer->tokenize(
            'foo.php',
            "<?php\n"
        );

        $rule = new MustStartWithOpenTag();
        $rule->check($file);

        $this->assertRuleViolations($file, array());
    }
}
