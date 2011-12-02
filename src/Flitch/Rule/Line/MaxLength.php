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
 * @subpackage Line
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule\Line;

use Flitch\Rule\AbstractRule,
    Flitch\File\File;

/**
 * Max line length rule.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @subpackage Line
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */
class MaxLength extends AbstractRule
{
    /**
     * Max line length limit.
     * 
     * @var integer
     */
    protected $limit = 80;
    
    /**
     * Defines how many spaces a tab takes up.
     * 
     * @var integer
     */
    protected $tabExpand = 4;
    
    /**
     * Set max line length limit.
     * 
     * @param  integer $length
     * @return MaxLength
     */
    public function setLimit($length)
    {
        $this->limit = (int) $length;
        return $this;
    }
    
    /**
     * Set how many spaces a tab takes up.
     * 
     * @param  integer $size
     * @return MaxLength
     */
    public function setTabExpand($size)
    {
        $this->tabExpand = (int) $size;
        return $this;
    }
    
    /**
     * check(): defined by Rule interface.
     * 
     * @see    Rule::check()
     * @param  File  $file
     * @return void
     */
    public function check(File $file)
    {
        foreach ($file->getLines() as $line => $data) {
            $lineLength = iconv_strlen(
                str_replace("\t", str_repeat(' ', $this->tabExpand),
                $data['content']),
                $file->getEncoding()
            );
            
            if ($lineLength > $this->limit) {
                $this->addViolation(
                    $file, $line, 0,
                    sprintf('Line may not be longer than %d characters', $this->limit)
                );
            }
        }
    }
}
