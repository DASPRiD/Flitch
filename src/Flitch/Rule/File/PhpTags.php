<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\File;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;

/**
 * PHP tags rule.
 */
class PhpTags extends AbstractRule
{
    /**
     * Map of tags to their names.
     *
     * @var array
     */
    protected static $tagMap = array(
        '<?php' => 'long',
        '<?'    => 'short',
        '<?='   => 'short-echo',
    );

    /**
     * Allowed PHP tags.
     *
     * @var array
     */
    protected $allowed = array('long', 'short', 'short-echo');

    /**
     * Whether to disallow closing tags.
     *
     * @var boolean
     */
    protected $disallowClosingTag = false;

    /**
     * Set allowed PHP tags.
     *
     * @param  string|array $allowed
     * @return PhpTags
     */
    public function setAllowed($allowed)
    {
        if (!is_array($allowed)) {
            $allowed = array_map('trim', explode(',', $allowed));
        }

        $this->allowed = array_intersect($allowed, array('long', 'short', 'short-echo'));
        return $this;
    }

    /**
     * Set whether to disallow closing tags.
     *
     * @param  boolean $disallowClosingTag
     * @return PhpTags
     */
    public function setDisallowClosingTag($disallowClosingTag)
    {
        $this->disallowClosingTag = (bool) $disallowClosingTag;
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
        $file->rewind();

        while ($file->seekTokenType(T_OPEN_TAG)) {
            $token = $file->current();

            $tag  = trim($token->getLexeme());
            $name = self::$tagMap[$tag];

            if (!in_array($name, $this->allowed)) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    sprintf('PHP tag "%s" is not allowed', $tag)
                );
            }

            $file->next();
        }

        if ($this->disallowClosingTag) {
            $file->rewind();

            if ($file->seekTokenType(T_CLOSE_TAG)) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    'Closing PHP tag found'
                );
            }
        }
    }
}
