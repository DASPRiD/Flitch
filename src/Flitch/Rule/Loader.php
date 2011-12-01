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
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
 */

namespace Flitch\Rule;

/**
 * Rule loader.
 * 
 * @category   Flitch
 * @package    Flitch_Rule
 * @copyright  Copyright (c) 2011 Ben Scholzen <mail@dasprids.de>
 * @license    New BSD License
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
