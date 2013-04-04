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
use Flitch\Rule\TokenRuleInterface;

/**
 * PHP tags rule.
 */
class PhpTags extends AbstractRule implements TokenRuleInterface
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
     * getListenerTokens(): defined by TokenRuleInterface.
     *
     * @see    TokenRuleInterface::getListenerTokens()
     * @return array
     */
    public function getListenerTokens()
    {
        return array(
            T_OPEN_TAG,
            T_CLOSE_TAG,
        );
    }

    /**
     * visitToken(): defined by TokenRuleInterface.
     *
     * @see    TokenRuleInterface::visitToken()
     * @param  File $file
     * @return void
     */
    public function visitToken(File $file)
    {
        $token = $token->current();

        if ($token->getType() === T_OPEN_TAG) {
            $tag  = trim($token->getLexeme());
            $name = self::$tagMap[$tag];

            if (!in_array($name, $this->allowed)) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    sprintf('PHP tag "%s" is not allowed', $tag)
                );
            }
        } elseif ($this->disallowClosingTag) {
            $this->addViolation(
                $file, $token->getLine(), $token->getColumn(),
                'Closing PHP tag found'
            );
        }
    }
}
