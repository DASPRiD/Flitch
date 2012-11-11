<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben 'DASPRiD' Scholzen
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule;

/**
 * Rule loader.
 */
class Loader
{
    /**
     * Global standards path.
     *
     * @var string
     */
    protected $globalPath;

    /**
     * Local standards path.
     *
     * @var string
     */
    protected $localPath;

    /**
     * Create a new rule loader.
     *
     * @param  string $globalPath
     * @param  string $localPath
     * @return void
     */
    public function __construct($globalPath, $localPath)
    {
        $this->globalPath = rtrim($globalPath, '/\\');
        $this->localPath  = rtrim($localPath, '/\\');
    }

    /**
     * Load a rule.
     *
     * @param  string $ruleName
     * @return Rule
     */
    public function load($ruleName)
    {
        $segments  = explode('.', $ruleName);

        $namespace = array_shift($segments);

        if ($namespace === 'Flitch') {
            $classname = 'Flitch\Rule\\' . implode('\\', $segments);
            $filename  = __DIR__ . '/' . implode('/', $segments) . '.php';
        } else {
            $classname = $namespace . '/' . implode('\\', $segments);
            $filename  = $this->localPath . '/' . implode('/', $segments) . '.php';

            if (!file_exists($filename)) {
                $filename  = $this->globalPath . '/' . implode('/', $segments) . '.php';
            }
        }

        if (!file_exists($filename) || !is_readable($filename)) {
            return null;
        }

        require_once $filename;

        if (!class_exists($classname, false)) {
            return null;
        }

        $rule = new $classname();

        if (!$rule instanceof Rule) {
            return null;
        }

        return $rule;
    }
}
