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
 * Must follow PSR0 rule.
 */
class MustFollowPsr0 extends AbstractRule
{
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

        while ($file->seekTokenType(T_CLASS)) {
            if (!$file->seekTokenType(T_STRING)) {
                $file->next();
                continue;
            }

            $token = $file->current();

            if ($token->getNamespace() !== null) {
                $fqcn = $token->getNamespace() . '\\' . $token->getLexeme();
            } else {
                $fqcn = $token->getLexeme();
            }

            $psr0Compliant     = true;
            $expectedPathParts = array_diff(explode('/', str_replace(array('\\', '_'), '/', $fqcn)), array(''));
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

            if (!$psr0Compliant) {
                $this->addViolation(
                    $file, $token->getLine(), $token->getColumn(),
                    sprintf('Class name "%s" is not PSR0 compliant', $fqcn)
                );
            }

            $file->next();
        }
    }
}