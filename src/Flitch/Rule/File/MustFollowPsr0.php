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
 * Must follow PSR0 rule.
 */
class MustFollowPsr0 extends AbstractRule implements TokenRuleInterface
{
    /**
     * Whether to require a vendor namespace.
     *
     * @var boolean
     */
    protected $requireVendorNamespace = true;

    /**
     * Set whether to require a vendor namespace.
     *
     * PSR-0 itself requires a vendor namespace. Since this is not possible in
     * legacy PHP 5.2 code, this option exists as fallback.
     *
     * @param  boolean $flag
     * @return MustFollowPsr0
     */
    public function requireVendorNamespace($requireVendorNamespace)
    {
        $this->requireVendorNamespace = (bool) $requireVendorNamespace;
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

        $token         = $file->current();
        $psr0Compliant = true;

        if ($token->getNamespace() !== null) {
            $fqcn = $token->getNamespace() . '\\' . $token->getLexeme();
            $path = str_replace('\\', '/', $token->getNamespace())
                  . '/' . str_replace('_', '/', $token->getLexeme());
        } else {
            $fqcn = $token->getLexeme();
            $path = str_replace('_', '/', $token->getLexeme());

            if ($this->requireVendorNamespace) {
                $psr0Compliant = false;
            }
        }

        if ($psr0Compliant) {
            $expectedPathParts = array_diff(explode('/', $path), array(''));
            $expectedFilename  = array_pop($expectedPathParts) . '.php';

            $pathParts = explode('/', str_replace('\\', '/', realpath($file->getFilename())));
            $filename  = array_pop($pathParts);

            if ($filename !== $expectedFilename) {
                // Class name should match filename.
                $psr0Compliant = false;
            } elseif (count($expectedPathParts) === 0) {
                // Vendor level namespace required.
                $psr0Compliant = false;
            } else {
                // Path should match namespace structure.
                $pathParts = array_slice($pathParts, -count($expectedPathParts));

                if ($pathParts !== $expectedPathParts) {
                    $psr0Compliant = false;
                }
            }
        }

        if (!$psr0Compliant) {
            $this->addViolation(
                $file, $token->getLine(), $token->getColumn(),
                sprintf('Class name "%s" is not PSR0 compliant', $fqcn)
            );
        }
    }
}
