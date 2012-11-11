<?php
/**
 * Flitch
 *
 * @link      http://github.com/DASPRiD/Flitch For the canonical source repository
 * @copyright 2011-2012 Ben Scholzen 'DASPRiD'
 * @license   http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */

namespace Flitch\Rule;

use Flitch\Exception;
use Flitch\File\File;

/**
 * Rule manager.
 */
class Manager
{
    /**
     * Rule loader.
     *
     * @var Loader
     */
    protected $loader;

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
     * Name of the current standard.
     *
     * @var string
     */
    protected $standard;

    /**
     * Rules in current standard.
     *
     * @var string
     */
    protected $rules = array();

    /**
     * Create a new rule manager.
     *
     * @param  string $globalPath
     * @param  string $localPath
     * @return void
     */
    public function __construct($globalPath, $localPath, $standard)
    {
        $this->loader     = new Loader($globalPath, $localPath);
        $this->globalPath = rtrim($globalPath, '/\\');
        $this->localPath  = rtrim($localPath, '/\\');
        $this->standard   = $standard;

        $this->loadStandard();
    }

    /**
     * Check a given file for all rules.
     *
     * @param  File $file
     * @return void
     */
    public function check(File $file)
    {
        foreach ($this->rules as $rule) {
            $rule->check($file);
        }
    }

    /**
     * Load a coding standard.
     *
     * @return void
     */
    protected function loadStandard()
    {
        $standard = $this->loadStandardFile($this->standard);

        foreach ($standard as $ruleName => $options) {
            $rule = $this->loader->load($ruleName);

            if ($rule === null) {
                throw new Exception\RuntimeException(sprintf('Could not load rule "%s"', $ruleName));
            }

            foreach ($options as $key => $value) {
                $setter = 'set' . ucfirst(preg_replace('(_([a-z]))e', 'strtoupper("\1")', strtolower($key)));

                if (method_exists($rule, $setter)) {
                    $rule->{$setter}($value);
                }
            }

            $this->rules[] = $rule;
        }
    }

    /**
     * Load a coding standard.
     *
     * @param  string $name
     * @return array
     */
    protected function loadStandardFile($name)
    {
        $filename = $this->localPath . '/' . $name . '/standard.ini';

        if (!file_exists($filename)) {
            $filename = $this->globalPath . '/' . $name . '/standard.ini';

            if (!file_exists($filename)) {
                throw new Exception\RuntimeException(sprintf('Could not find standard "%s"', $name));
            }
        }

        if (!is_readable($filename)) {
            throw new Exception\RuntimeException(sprintf('Standard "%s" is not readable', $name));
        }

        $standard = @parse_ini_file($filename, true);

        if ($standard === false) {
            throw new Exception\RuntimeException(sprintf('Could not load standard "%s"', $name));
        }

        if (isset($standard['extends'])) {
            $extends = array_map('trim', explode(',', $standard['extends']));

            foreach ($extends as $extend) {
                $standard = array_replace_recursive($this->loadStandardFile($extend), $standard);
            }

            unset($standard['extends']);
        }

        return $standard;
    }
}
