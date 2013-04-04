<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule\Naming;

use Flitch\File\File;
use Flitch\Rule\AbstractRule;
use Flitch\Rule\TokenRuleInterface;

/**
 * Class name rule.
 */
class Classes extends AbstractRule implements TokenRuleInterface
{
    /**
     * Class name format.
     *
     * @var type
     */
    protected $format = '[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*';

    /**
     * Set format.
     *
     * @param  string $format
     * @return Classes
     */
    public function setFormat($format)
    {
        $this->format = $format;
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
            T_CLASS,
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
        if (!$file->seekTokenType(T_STRING)) {
            return;
        }

        $token = $file->current();

        if (!preg_match('(^' . $this->format . '$)', $token->getLexeme())) {
            $this->addViolation(
                $file, $token->getLine(), $token->getColumn(),
                sprintf('Class name does not match format "%s"', $this->format)
            );
        }
    }
}
