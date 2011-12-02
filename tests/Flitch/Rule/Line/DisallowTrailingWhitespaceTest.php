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

namespace FlitchTest\Rule\Line;

use Flitch\Test\RuleTestCase,
    Flitch\File\File,
    Flitch\Rule\Line\DisallowTrailingWhitespace;

/**
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class DisallowTrailingWhitespaceTest extends RuleTestCase
{  
    public function testTrailingWhitespace()
    {
        $this->file = new File(
            'foo.php',
            "<?php \n\t\n// foo"
        );
        
        $rule = new DisallowTrailingWhitespace();
        $rule->check($this->file);
        
        $this->assertRuleViolations($this->file, array(
            array(
                'line'     => 1,
                'column'   => 0,
                'message'  => 'Line may not contain trailing whitespace',
                'source'   => 'Flitch\Line\DisallowTrailingWhitespace'
            ),
            array(
                'line'     => 2,
                'column'   => 0,
                'message'  => 'Line may not contain trailing whitespace',
                'source'   => 'Flitch\Line\DisallowTrailingWhitespace'
            ),
        ));
    }
}
